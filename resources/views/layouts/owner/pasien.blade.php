@extends('layouts.owner.app')

@section('pageTitle', 'Daftar Pasien')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<div class="font-['Roboto',sans-serif]">

    {{-- Header Filter --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 pt-6">

        {{-- Search + Buttons sejajar --}}
        <div class="flex w-full items-center justify-between gap-3">
            {{-- Search --}}
            <div class="relative flex-grow">
                <input type="text" placeholder="Search patient"
                    class="border border-white rounded-full pl-10 pr-4 py-2 w-full sm:w-60 focus:ring-2 focus:ring-[#EED892] focus:outline-none text-sm">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-500"></i>
            </div>

            {{-- Buttons di kanan --}}
            <div class="flex space-x-2 sm:space-x-3 shrink-0">
                {{-- Export --}}
                <button
                    class="flex items-center justify-center bg-[#806B3F] text-white px-3 sm:px-4 py-2 rounded-lg hover:bg-[#A18F5E] transition-all duration-300 text-sm shadow-sm hover:shadow-md">
                    <i class="fa-solid fa-file-export text-base sm:mr-2"></i>
                    <span class="hidden sm:inline">Export</span>
                </button>

                {{-- Add Patient --}}
                    <a href="{{ route('owner.pasien.add') }}"     
                    class="flex items-center justify-center bg-[#806B3F] text-white px-3 sm:px-4 py-2 rounded-lg hover:bg-[#A18F5E] transition-all duration-300 text-sm shadow-sm hover:shadow-md">
                    <i class="fa-solid fa-plus text-base sm:mr-2"></i>
                    <span class="hidden sm:inline">Add Patient</span>
                 </a>
            </div>
        </div>
    </div>


    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow p-4 sm:p-6 overflow-x-auto">
        <table class="min-w-[700px] w-full text-sm text-left table-bordered border-collapse">
            <thead>
                <tr class="bg-gray-100/60 text-gray-700 font-normal">
                    <th class="px-4 py-3 rounded-l-lg border-b border-gray-300">No</th>
                    <th class="px-4 py-3 border-b border-gray-300">ID Patient</th>
                    <th class="px-4 py-3 border-b border-gray-300">Name</th>
                    <th class="px-4 py-3 border-b border-gray-300">Phone</th>
                    <th class="px-4 py-3 border-b border-gray-300">Email</th>
                    <th class="px-4 py-3 border-b border-gray-300">Tanggal Lahir</th>
                    <th class="px-4 py-3 border-b border-gray-300">Gender</th>
                    <th class="px-4 py-3 border-b border-gray-300">Rekam Medis</th>
                    <th class="px-4 py-3 rounded-r-lg border-b border-gray-300">Action</th>
                </tr>
            </thead>

            <tbody class="text-gray-700">
                @foreach([
                    ['no'=>1,'id'=>'P-001','name'=>'Susi Susanti','phone'=>'62822345678','email'=>'susi@gmail.com','dob'=>'24 Maret 2000','gender'=>'Female','medrec'=>'See Details'],
                    ['no'=>2,'id'=>'P-002','name'=>'Aliyah Runa','phone'=>'62822345678','email'=>'aliyah@gmail.com','dob'=>'21 Juni 1999','gender'=>'Female','medrec'=>'See Details'],
                    ['no'=>3,'id'=>'P-003','name'=>'Asep','phone'=>'62822345678','email'=>'asep@gmail.com','dob'=>'27 Februari 2004','gender'=>'Male','medrec'=>'See Details'],
                    ['no'=>4,'id'=>'P-004','name'=>'Rudi Hartono','phone'=>'62822345678','email'=>'rudi@gmail.com','dob'=>'30 Oktober 2002','gender'=>'Male','medrec'=>'See Details'],
                ] as $row)
                    <tr class="bg-white hover:bg-gray-50 transition-all duration-200 border-b border-gray-200">
                        <td class="px-4 py-3">{{ $row['no'] }}</td>
                        <td class="px-4 py-3">{{ $row['id'] }}</td>
                        <td class="px-4 py-3">{{ $row['name'] }}</td>
                        <td class="px-4 py-3">{{ $row['phone'] }}</td>
                        <td class="px-4 py-3">{{ $row['email'] }}</td>
                        <td class="px-4 py-3">{{ $row['dob'] }}</td>
                        <td class="px-4 py-3">{{ $row['gender'] }}</td>

                        {{-- Rekam Medis --}}
                        <td class="px-4 py-3">
                            <a href="{{ route('owner.pasien.detail') }}"
                                class="bg-[#F5EAD5] text-black font-normal px-3 py-1 rounded-lg text-xs hover:bg-[#EED892] transition-all duration-200">
                                See Details
                            </a>
                        </td>


                       {{-- Action --}}
                        <td class="px-4 py-3 flex space-x-3">
                            <a href="{{ route('owner.pasien.editRekam') }}" class="text-gray-700 hover:text-black transition-all duration-200">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <button class="text-gray-700 hover:text-black transition-all duration-200">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Animasi kecil --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach((row, index) => {
                row.style.opacity = 0;
                row.style.transform = 'translateY(10px)';
                setTimeout(() => {
                    row.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    row.style.opacity = 1;
                    row.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>

</div>
@endsection
