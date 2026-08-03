@extends('layouts.app')

@section('title', 'Data Karyawan & Cuti')

@section('content')
<div class="min-h-screen flex flex-col bg-gray-50 dark:bg-gray-900">
    <header class="w-full py-4 px-6 md:px-10 border-b border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 flex justify-between items-center shadow-sm">
        <div class="flex items-center gap-3">
            <span class="font-bold text-gray-700 dark:text-gray-300">Penghitung Cuti Karyawan</span>
        </div>
    </header>

    <main x-data="employeeSearch" class="flex-grow max-w-7xl mx-auto w-full px-6 py-10 flex flex-col gap-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Daftar Karyawan</h2>
            
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <div class="flex-grow sm:flex-grow-0 flex items-center gap-2 relative">
                    <x-input type="text" x-model="query" @input.debounce.500ms="search" placeholder="Cari NIK atau Nama..." class="w-full sm:w-64" />
                    <div x-show="loading" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                        <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                </div>

                <x-button variant="primary" @click="$dispatch('open-modal', 'import-modal')" class="shrink-0">
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Import Excel
                </x-button>
            </div>
        </div>

        <x-card>
            <div id="table-container">
                <div class="overflow-x-auto">
                    <x-table :headers="['EMPL.ID', 'Nama', 'Jabatan', 'Tgl Bergabung', 'Jatah Cuti', 'Cuti Terpakai', 'Sisa Cuti', 'Aksi']">
                        @forelse($employees as $employee)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><div class="text-sm font-medium text-gray-900 dark:text-white">{{ $employee->employee_id }}</div></td>
                            <td class="px-6 py-4 whitespace-nowrap"><div class="font-semibold text-gray-900 dark:text-white">{{ $employee->name }}</div></td>
                            <td class="px-6 py-4 whitespace-nowrap"><div class="text-sm text-gray-500">{{ $employee->position }}</div></td>
                            <td class="px-6 py-4 whitespace-nowrap"><div class="text-sm text-gray-500">{{ $employee->join_date->format('d/m/Y') }}</div></td>
                            <td class="px-6 py-4 whitespace-nowrap text-center"><div class="text-sm font-semibold">{{ $employee->leave_quota }}</div></td>
                            <td class="px-6 py-4 whitespace-nowrap text-center"><div class="text-sm font-semibold text-gray-500">{{ $employee->leave_taken }}</div></td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $employee->remaining_leave > 0 ? 'bg-green-100 text-brand-dark' : 'bg-red-100 text-red-800' }}">
                                    {{ $employee->remaining_leave }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button @click="$dispatch('open-modal', 'edit-modal'); $dispatch('set-edit-employee', { id: '{{ $employee->id }}', name: '{{ addslashes($employee->name) }}', taken: {{ $employee->leave_taken }} })" class="text-gray-400 hover:text-brand-light transition-colors" title="Ubah Cuti Terpakai">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center">
                                <x-empty-state title="Data tidak ditemukan" description="Tidak ada karyawan yang cocok dengan pencarian Anda.">
                                    <x-slot name="action">
                                        <x-button variant="outline" @click="query = ''; search()">Reset Pencarian</x-button>
                                    </x-slot>
                                </x-empty-state>
                            </td>
                        </tr>
                        @endforelse
                    </x-table>
                </div>
                
                <div class="mt-4">
                    {{ $employees->links() }}
                </div>
            </div>
        </x-card>
    </main>
</div>

<!-- Import Modal -->
<x-modal name="import-modal" maxWidth="md">
    <div class="p-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Import Data Karyawan</h2>
        <form action="{{ route('employees.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <x-file-upload name="file" label="Pilih File Excel (.xlsx, .xls)" accept=".xlsx,.xls" />
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <x-button type="button" variant="outline" @click="$dispatch('close-modal', 'import-modal')">Batal</x-button>
                <x-button type="submit" variant="primary">Import</x-button>
            </div>
        </form>
    </div>
</x-modal>

<!-- Edit Modal -->
<div x-data="{ editId: '', editName: '', editTaken: 0 }" 
     @set-edit-employee.window="editId = $event.detail.id; editName = $event.detail.name; editTaken = $event.detail.taken;">
    <x-modal name="edit-modal" maxWidth="sm">
        <div class="p-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Ubah Cuti Terpakai</h2>
            <p class="text-sm text-gray-500 mb-6" x-text="'Karyawan: ' + editName"></p>
            <form :action="'{{ url('employees') }}/' + editId" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Jumlah Cuti Terpakai</label>
                    <x-input type="number" name="leave_taken" x-model="editTaken" min="0" required />
                </div>
                <div class="flex justify-end gap-3">
                    <x-button type="button" variant="outline" @click="$dispatch('close-modal', 'edit-modal')">Batal</x-button>
                    <x-button type="submit" variant="primary">Simpan</x-button>
                </div>
            </form>
        </div>
    </x-modal>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('employeeSearch', () => ({
            query: '{{ request('search') }}',
            loading: false,
            search() {
                this.loading = true;
                let url = new URL(window.location.href);
                
                if (this.query) {
                    url.searchParams.set('search', this.query);
                    url.searchParams.delete('page');
                } else {
                    url.searchParams.delete('search');
                    url.searchParams.delete('page');
                }
                
                // Fetch new table data from server
                fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    // Replace the table container content
                    document.getElementById('table-container').innerHTML = doc.getElementById('table-container').innerHTML;
                    
                    // Update the URL without a page reload
                    window.history.pushState({}, '', url);
                })
                .catch(err => console.error('Search failed:', err))
                .finally(() => {
                    this.loading = false;
                });
            }
        }));
    });
</script>
@endsection
