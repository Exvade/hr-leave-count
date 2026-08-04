@extends('layouts.app')

@section('title', 'Data Karyawan & Cuti')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full flex flex-col gap-8" x-data="employeeSearch">
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 relative z-10">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Daftar Karyawan</h1>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Kelola kuota cuti tahunan dan riwayat pengambilan cuti seluruh karyawan.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
            <div class="w-full sm:w-72 relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-brand-light transition-colors duration-300">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" x-model="query" @input.debounce.500ms="search" placeholder="Cari NIK atau Nama..." 
                    class="w-full pl-11 pr-10 py-2.5 bg-white/70 dark:bg-gray-800/70 backdrop-blur-md border border-gray-200/50 dark:border-gray-700/50 focus:ring-2 focus:ring-brand-light focus:border-transparent rounded-xl transition-all duration-300 outline-none shadow-sm dark:text-white" />
                <div x-show="loading" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                    <svg class="animate-spin h-5 w-5 text-brand-light" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </div>
            </div>

            <button @click="$dispatch('open-modal', 'import-modal')" class="w-full sm:w-auto flex justify-center items-center gap-2 whitespace-nowrap bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm font-semibold px-4 py-2.5 rounded-xl shadow-sm transition-all duration-300">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Data Karyawan
            </button>

            <button @click="$dispatch('open-modal', 'import-leave-modal')" class="w-full sm:w-auto flex justify-center items-center gap-2 whitespace-nowrap bg-white dark:bg-gray-800 border border-brand-light/30 dark:border-brand-light/20 text-brand-light dark:text-brand-light hover:bg-brand-light/10 dark:hover:bg-brand-light/10 text-sm font-semibold px-4 py-2.5 rounded-xl shadow-sm transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Rekap Cuti
            </button>

            <button @click="$dispatch('open-modal', 'create-modal')" class="w-full sm:w-auto flex justify-center items-center gap-2 whitespace-nowrap bg-gradient-to-r from-brand-dark to-brand-light hover:from-brand-light hover:to-brand-dark text-white text-sm font-semibold px-6 py-2.5 rounded-xl shadow-lg shadow-brand-light/20 hover:shadow-brand-light/40 transform hover:-translate-y-0.5 transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Karyawan
            </button>
        </div>
    </div>

    <!-- Glassmorphism Table Container -->
    <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 shadow-xl rounded-2xl overflow-hidden transition-all duration-300 relative z-10">
        <div id="table-container">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/80 dark:bg-gray-900/80 border-b border-gray-200/50 dark:border-gray-700/50 text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-semibold">
                            <th class="px-6 py-4">EMPL.ID</th>
                            <th class="px-6 py-4">Nama</th>
                            <th class="px-6 py-4">Jabatan</th>
                            <th class="px-6 py-4 text-center">Tgl Bergabung</th>
                            <th class="px-6 py-4 text-center">Jatah Cuti</th>
                            <th class="px-6 py-4 text-center">Terpakai</th>
                            <th class="px-6 py-4 text-center">Sisa Cuti</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200/30 dark:divide-gray-700/30">
                        @forelse($employees as $employee)
                        <tr class="hover:bg-brand-light/5 dark:hover:bg-brand-light/10 transition-colors duration-200 group">
                            <td class="px-6 py-4 whitespace-nowrap"><div class="text-sm font-medium text-gray-900 dark:text-white">{{ $employee->employee_id }}</div></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-bold text-gray-900 dark:text-white group-hover:text-brand-light transition-colors">{{ $employee->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap"><div class="text-sm text-gray-500 dark:text-gray-400">{{ $employee->position }}</div></td>
                            <td class="px-6 py-4 whitespace-nowrap text-center"><div class="text-sm text-gray-500 dark:text-gray-400">{{ $employee->join_date->format('d/m/Y') }}</div></td>
                            <td class="px-6 py-4 whitespace-nowrap text-center"><div class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $employee->leave_quota }}</div></td>
                            <td class="px-6 py-4 whitespace-nowrap text-center"><div class="text-sm font-bold text-amber-600 dark:text-amber-500">{{ $employee->leave_taken }}</div></td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($employee->remaining_leave > 0)
                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100/80 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50 shadow-sm">
                                    {{ $employee->remaining_leave }}
                                </span>
                                @else
                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-bold bg-rose-100/80 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400 border border-rose-200 dark:border-rose-800/50 shadow-sm animate-pulse">
                                    {{ $employee->remaining_leave }}
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-2">
                                    @php
                                        $details = json_encode($employee->leave_details);
                                        $history = $employee->leaveRecords->toJson();
                                    @endphp
                                    <button @click="$dispatch('open-modal', 'detail-modal'); $dispatch('set-detail-employee', { name: '{{ addslashes($employee->name) }}', details: {{ $details }}, history: {{ $history }} })" 
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-400 hover:text-white hover:bg-blue-500 hover:shadow-md hover:shadow-blue-500/30 transform hover:scale-110 transition-all duration-200" title="Detail Cuti">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </button>
                                    <button @click="$dispatch('open-modal', 'edit-modal'); $dispatch('set-edit-employee', { id: '{{ $employee->id }}', name: '{{ addslashes($employee->name) }}', position: '{{ addslashes($employee->position) }}' })" 
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-400 hover:text-white hover:bg-brand-light hover:shadow-md hover:shadow-brand-light/30 transform hover:scale-110 transition-all duration-200" title="Ubah Data">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    <button @click="confirmDelete('{{ $employee->id }}', '{{ addslashes($employee->name) }}')"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-400 hover:text-white hover:bg-red-500 hover:shadow-md hover:shadow-red-500/30 transform hover:scale-110 transition-all duration-200" title="Hapus Data">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">Data tidak ditemukan</h3>
                                    <p class="text-gray-500 dark:text-gray-400 mb-4 text-sm">Tidak ada karyawan yang cocok dengan kriteria pencarian Anda.</p>
                                    <button @click="query = ''; search()" class="text-sm font-medium text-brand-light hover:text-brand-dark transition-colors">
                                        Reset Pencarian
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($employees->hasPages())
            <div class="px-6 py-4 border-t border-gray-200/50 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-900/50">
                {{ $employees->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Import Modal -->
<x-modal name="import-modal" maxWidth="md">
    <div class="p-6">
        <h2 class="text-xl font-extrabold text-gray-900 dark:text-white mb-2">Import Data Karyawan</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Unggah file Excel (.xlsx atau .xls) yang berisi daftar karyawan.</p>
        <form action="{{ route('employees.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-6">
                <x-file-upload name="file" label="" accept=".xlsx,.xls" />
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" @click="$dispatch('close-modal', 'import-modal')" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 dark:text-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-xl transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-brand-light hover:bg-brand-dark rounded-xl shadow-md shadow-brand-light/20 transition-all">
                    Mulai Import
                </button>
            </div>
        </form>
    </div>
</x-modal>

<!-- Import Rekap Cuti Modal -->
<x-modal name="import-leave-modal" maxWidth="md">
    <div class="p-6">
        <h2 class="text-xl font-extrabold text-gray-900 dark:text-white mb-2">Import Rekap Cuti</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Unggah file Excel yang berisi riwayat pengambilan cuti (Durasi akan diakumulasikan ke Cuti Terpakai).</p>
        <form action="{{ route('employees.import-leaves') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-6">
                <x-file-upload name="file" label="" accept=".xlsx,.xls" />
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" @click="$dispatch('close-modal', 'import-leave-modal')" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 dark:text-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-xl transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-brand-light hover:bg-brand-dark rounded-xl shadow-md shadow-brand-light/20 transition-all">
                    Mulai Import
                </button>
            </div>
        </form>
    </div>
</x-modal>

<!-- Create Modal -->
<x-modal name="create-modal" maxWidth="md">
    <div class="p-6">
        <h2 class="text-xl font-extrabold text-gray-900 dark:text-white mb-6">Tambah Karyawan Baru</h2>
        <form action="{{ route('employees.store') }}" method="POST">
            @csrf
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-300">ID Karyawan (EMPL.ID)</label>
                    <input type="text" name="employee_id" required placeholder="Contoh: MWT-001"
                        class="w-full py-2.5 px-4 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 focus:ring-2 focus:ring-brand-light rounded-xl transition-all outline-none dark:text-white" />
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-300">Nama Lengkap</label>
                    <input type="text" name="name" required placeholder="Masukkan nama..."
                        class="w-full py-2.5 px-4 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 focus:ring-2 focus:ring-brand-light rounded-xl transition-all outline-none dark:text-white" />
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-300">Jabatan</label>
                    <input type="text" name="position" required placeholder="Contoh: Staff IT"
                        class="w-full py-2.5 px-4 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 focus:ring-2 focus:ring-brand-light rounded-xl transition-all outline-none dark:text-white" />
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-300">Tanggal Bergabung</label>
                    <input type="date" name="join_date" required 
                        class="w-full py-2.5 px-4 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 focus:ring-2 focus:ring-brand-light rounded-xl transition-all outline-none dark:text-white" />
                </div>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" @click="$dispatch('close-modal', 'create-modal')" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 dark:text-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-xl transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-brand-light hover:bg-brand-dark rounded-xl shadow-md shadow-brand-light/20 transition-all">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</x-modal>

<!-- Edit Modal -->
<div x-data="{ editId: '', editName: '', editPosition: '' }" 
     @set-edit-employee.window="editId = $event.detail.id; editName = $event.detail.name; editPosition = $event.detail.position;">
    <x-modal name="edit-modal" maxWidth="md">
        <div class="p-6">
            <h2 class="text-xl font-extrabold text-gray-900 dark:text-white mb-6">Ubah Data Karyawan</h2>
            
            <form :action="'{{ url('employees') }}/' + editId" method="POST" class="mb-8">
                @csrf
                @method('PUT')
                <div class="space-y-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-300">Nama Lengkap</label>
                        <input type="text" name="name" x-model="editName" required 
                            class="w-full py-2.5 px-4 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 focus:ring-2 focus:ring-brand-light rounded-xl transition-all outline-none dark:text-white" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-300">Jabatan</label>
                        <input type="text" name="position" x-model="editPosition" required 
                            class="w-full py-2.5 px-4 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 focus:ring-2 focus:ring-brand-light rounded-xl transition-all outline-none dark:text-white" />
                        <p class="mt-2 text-xs text-gray-500">*Cuti Terpakai kini dihitung secara otomatis berdasarkan riwayat Rekap Cuti.</p>
                    </div>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-blue-500 hover:bg-blue-600 rounded-xl shadow-md transition-all">
                        Simpan Profil
                    </button>
                </div>
            </form>

            <hr class="border-gray-200 dark:border-gray-700 mb-6">

            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Tambah Riwayat Cuti Manual</h3>
            <form :action="'{{ url('employees') }}/' + editId + '/leaves'" method="POST">
                @csrf
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-300">Tgl Mulai</label>
                        <input type="date" name="start_date" required class="w-full py-2 px-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 focus:ring-2 focus:ring-brand-light rounded-lg transition-all outline-none dark:text-white text-sm" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-300">Tgl Selesai</label>
                        <input type="date" name="end_date" required class="w-full py-2 px-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 focus:ring-2 focus:ring-brand-light rounded-lg transition-all outline-none dark:text-white text-sm" />
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-300">Durasi (Hari)</label>
                        <input type="number" name="duration" min="1" required class="w-full py-2 px-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 focus:ring-2 focus:ring-brand-light rounded-lg transition-all outline-none dark:text-white text-sm" />
                    </div>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" @click="$dispatch('close-modal', 'edit-modal')" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 dark:text-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-xl transition-colors">
                        Tutup
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-brand-light hover:bg-brand-dark rounded-xl shadow-md transition-all">
                        Simpan Cuti
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
</div>

<!-- Detail Modal -->
<div x-data="{ detailName: '', d: {}, history: [] }" 
     @set-detail-employee.window="detailName = $event.detail.name; d = $event.detail.details; history = $event.detail.history;">
    <x-modal name="detail-modal" maxWidth="4xl">
        <div class="p-6 sm:p-8">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-1">Rincian Saldo & Riwayat Cuti</h2>
                    <p class="text-base font-semibold text-brand-light" x-text="detailName"></p>
                </div>
                <button type="button" @click="$dispatch('close-modal', 'detail-modal')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 rounded-full p-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Kolom Kiri: Kalkulasi Saldo -->
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700/50 pb-3 mb-4 uppercase tracking-wider">Kalkulasi Saldo Cuti</h3>
                    <div class="space-y-3 bg-gray-50/50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-800">
                        <div class="flex justify-between items-center py-2 border-b border-gray-200/50 dark:border-gray-700/50">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Anniversary Saat Ini</span>
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200" x-text="d.anniv_saat_ini"></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-200/50 dark:border-gray-700/50">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Hak Periode Sebelumnya</span>
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200"><span x-text="d.hak_periode_sebelumnya"></span> Hari</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-200/50 dark:border-gray-700/50">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Dipakai Periode Sebelumnya</span>
                            <span class="text-sm font-semibold text-red-500"><span x-text="d.dipakai_periode_sebelumnya"></span> Hari</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-200/50 dark:border-gray-700/50">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Batas Pengambilan (Hangus)</span>
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200" x-text="d.batas_pengambilan"></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-200/50 dark:border-gray-700/50">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Status Saldo Sebelumnya</span>
                            <span class="text-xs font-bold px-2 py-1 rounded-full shadow-sm" 
                                :class="d.status_hangus === 'HANGUS' ? 'bg-red-100 text-red-600 dark:bg-red-900/40 dark:text-red-400' : 'bg-green-100 text-green-600 dark:bg-green-900/40 dark:text-green-400'"
                                x-text="d.status_hangus"></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-200/50 dark:border-gray-700/50">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Hak Periode Berjalan</span>
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200"><span x-text="d.hak_periode_berjalan"></span> Hari</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-200/50 dark:border-gray-700/50">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Dipakai Periode Berjalan</span>
                            <span class="text-sm font-semibold text-red-500"><span x-text="d.dipakai_periode_berjalan"></span> Hari</span>
                        </div>
                        <div class="flex justify-between items-center pt-3 mt-2">
                            <span class="text-base font-bold text-gray-900 dark:text-white">Total Saldo Cuti</span>
                            <span class="text-xl font-black text-brand-light bg-brand-light/10 dark:bg-brand-light/20 px-3 py-1 rounded-lg"><span x-text="d.total_saldo"></span> Hari</span>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Tabel Riwayat -->
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700/50 pb-3 mb-4 uppercase tracking-wider">Riwayat Pengambilan Cuti</h3>
                    <div class="max-h-[380px] overflow-y-auto border border-gray-200 dark:border-gray-700 rounded-xl shadow-inner bg-white dark:bg-gray-900">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800 sticky top-0 z-10 shadow-sm">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Mulai</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Selesai</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-bold text-brand-light uppercase tracking-wider">Durasi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                <template x-if="history.length === 0">
                                    <tr>
                                        <td colspan="3" class="px-4 py-8 text-sm text-gray-500 dark:text-gray-400 text-center flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                            <span class="font-medium">Belum ada riwayat cuti</span>
                                        </td>
                                    </tr>
                                </template>
                                <template x-for="record in history" :key="record.id">
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200" x-text="new Date(record.start_date).toLocaleDateString('id-ID', {day: '2-digit', month: 'short', year: 'numeric'})"></td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200" x-text="new Date(record.end_date).toLocaleDateString('id-ID', {day: '2-digit', month: 'short', year: 'numeric'})"></td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-brand-light text-center bg-brand-light/5"><span x-text="record.duration"></span> Hr</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </x-modal>
</div>

<!-- Hidden Delete Form -->
<form id="delete-form" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

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
                
                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => res.text())
                .then(html => {
                    const doc = new DOMParser().parseFromString(html, 'text/html');
                    document.getElementById('table-container').innerHTML = doc.getElementById('table-container').innerHTML;
                    window.history.pushState({}, '', url);
                })
                .catch(err => console.error('Search failed:', err))
                .finally(() => { this.loading = false; });
            },
            confirmDelete(id, name) {
                Swal.fire({
                    title: 'Hapus Data?',
                    html: `Anda yakin ingin menghapus data <b>${name}</b>?<br>Tindakan ini tidak dapat dibatalkan.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('delete-form');
                        form.action = `{{ url('employees') }}/${id}`;
                        form.submit();
                    }
                });
            }
        }));
    });
</script>
@endsection
