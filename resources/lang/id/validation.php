<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'Field :attribute harus diterima.',
    'accepted_if' => 'Field :attribute harus diterima ketika :other adalah :value.',
    'active_url' => 'Field :attribute harus berupa URL yang valid.',
    'after' => 'Field :attribute harus berupa tanggal setelah :date.',
    'after_or_equal' => 'Field :attribute harus berupa tanggal setelah atau sama dengan :date.',
    'alpha' => 'Field :attribute hanya boleh berisi huruf.',
    'alpha_dash' => 'Field :attribute hanya boleh berisi huruf, angka, tanda penghubung, dan garis bawah.',
    'alpha_num' => 'Field :attribute hanya boleh berisi huruf dan angka.',
    'array' => 'Field :attribute harus berupa array.',
    'before' => 'Field :attribute harus berupa tanggal sebelum :date.',
    'before_or_equal' => 'Field :attribute harus berupa tanggal sebelum atau sama dengan :date.',
    'between' => [
        'numeric' => 'Field :attribute harus antara :min dan :max.',
        'file' => 'Field :attribute harus antara :min dan :max kilobyte.',
        'string' => 'Field :attribute harus antara :min dan :max karakter.',
        'array' => 'Field :attribute harus memiliki antara :min dan :max item.',
    ],
    'boolean' => 'Field :attribute harus berupa true atau false.',
    'confirmed' => 'Konfirmasi field :attribute tidak cocok.',
    'current_password' => 'Password saat ini tidak benar.',
    'date' => 'Field :attribute harus berupa tanggal yang valid.',
    'date_equals' => 'Field :attribute harus berupa tanggal yang sama dengan :date.',
    'date_format' => 'Field :attribute harus cocok dengan format :format.',
    'declined' => 'Field :attribute harus ditolak.',
    'declined_if' => 'Field :attribute harus ditolak ketika :other adalah :value.',
    'different' => 'Field :attribute dan :other harus berbeda.',
    'digits' => 'Field :attribute harus berupa :digits digit.',
    'digits_between' => 'Field :attribute harus antara :min dan :max digit.',
    'dimensions' => 'Field :attribute memiliki dimensi gambar yang tidak valid.',
    'distinct' => 'Field :attribute memiliki nilai duplikat.',
    'email' => 'Field :attribute harus berupa alamat email yang valid.',
    'ends_with' => 'Field :attribute harus diakhiri dengan salah satu dari: :values.',
    'enum' => 'Field :attribute yang dipilih tidak valid.',
    'exists' => 'Field :attribute yang dipilih tidak valid.',
    'file' => 'Field :attribute harus berupa file.',
    'filled' => 'Field :attribute harus memiliki nilai.',
    'gt' => [
        'numeric' => 'Field :attribute harus lebih besar dari :value.',
        'file' => 'Field :attribute harus lebih besar dari :value kilobyte.',
        'string' => 'Field :attribute harus lebih besar dari :value karakter.',
        'array' => 'Field :attribute harus memiliki lebih dari :value item.',
    ],
    'gte' => [
        'numeric' => 'Field :attribute harus lebih besar dari atau sama dengan :value.',
        'file' => 'Field :attribute harus lebih besar dari atau sama dengan :value kilobyte.',
        'string' => 'Field :attribute harus lebih besar dari atau sama dengan :value karakter.',
        'array' => 'Field :attribute harus memiliki :value item atau lebih.',
    ],
    'image' => 'Field :attribute harus berupa gambar.',
    'in' => 'Field :attribute yang dipilih tidak valid.',
    'in_array' => 'Field :attribute harus ada di :other.',
    'integer' => 'Field :attribute harus berupa bilangan bulat.',
    'ip' => 'Field :attribute harus berupa alamat IP yang valid.',
    'ipv4' => 'Field :attribute harus berupa alamat IPv4 yang valid.',
    'ipv6' => 'Field :attribute harus berupa alamat IPv6 yang valid.',
    'json' => 'Field :attribute harus berupa string JSON yang valid.',
    'lt' => [
        'numeric' => 'Field :attribute harus kurang dari :value.',
        'file' => 'Field :attribute harus kurang dari :value kilobyte.',
        'string' => 'Field :attribute harus kurang dari :value karakter.',
        'array' => 'Field :attribute harus memiliki kurang dari :value item.',
    ],
    'lte' => [
        'numeric' => 'Field :attribute harus kurang dari atau sama dengan :value.',
        'file' => 'Field :attribute harus kurang dari atau sama dengan :value kilobyte.',
        'string' => 'Field :attribute harus kurang dari atau sama dengan :value karakter.',
        'array' => 'Field :attribute tidak boleh memiliki lebih dari :value item.',
    ],
    'mac_address' => 'Field :attribute harus berupa alamat MAC yang valid.',
    'max' => [
        'numeric' => 'Field :attribute tidak boleh lebih besar dari :max.',
        'file' => 'Field :attribute tidak boleh lebih besar dari :max kilobyte.',
        'string' => 'Field :attribute tidak boleh lebih besar dari :max karakter.',
        'array' => 'Field :attribute tidak boleh memiliki lebih dari :max item.',
    ],
    'mimes' => 'Field :attribute harus berupa file dengan tipe: :values.',
    'mimetypes' => 'Field :attribute harus berupa file dengan tipe: :values.',
    'min' => [
        'numeric' => 'Field :attribute harus minimal :min.',
        'file' => 'Field :attribute harus minimal :min kilobyte.',
        'string' => 'Field :attribute harus minimal :min karakter.',
        'array' => 'Field :attribute harus memiliki minimal :min item.',
    ],
    'multiple_of' => 'Field :attribute harus kelipatan dari :value.',
    'not_in' => 'Field :attribute yang dipilih tidak valid.',
    'not_regex' => 'Format field :attribute tidak valid.',
    'numeric' => 'Field :attribute harus berupa angka.',
    'password' => 'Password tidak benar.',
    'present' => 'Field :attribute harus ada.',
    'prohibited' => 'Field :attribute dilarang.',
    'prohibited_if' => 'Field :attribute dilarang ketika :other adalah :value.',
    'prohibited_unless' => 'Field :attribute dilarang kecuali :other ada di :values.',
    'prohibits' => 'Field :attribute melarang :other ada.',
    'regex' => 'Format field :attribute tidak valid.',
    'required' => 'Field :attribute wajib diisi.',
    'required_array_keys' => 'Field :attribute harus berisi entri untuk: :values.',
    'required_if' => 'Field :attribute wajib diisi ketika :other adalah :value.',
    'required_unless' => 'Field :attribute wajib diisi kecuali :other ada di :values.',
    'required_with' => 'Field :attribute wajib diisi ketika :values ada.',
    'required_with_all' => 'Field :attribute wajib diisi ketika :values ada.',
    'required_without' => 'Field :attribute wajib diisi ketika :values tidak ada.',
    'required_without_all' => 'Field :attribute wajib diisi ketika tidak ada :values.',
    'same' => 'Field :attribute dan :other harus cocok.',
    'size' => [
        'numeric' => 'Field :attribute harus :size.',
        'file' => 'Field :attribute harus :size kilobyte.',
        'string' => 'Field :attribute harus :size karakter.',
        'array' => 'Field :attribute harus berisi :size item.',
    ],
    'starts_with' => 'Field :attribute harus dimulai dengan salah satu dari: :values.',
    'string' => 'Field :attribute harus berupa string.',
    'timezone' => 'Field :attribute harus berupa zona waktu yang valid.',
    'unique' => 'Field :attribute sudah digunakan.',
    'uploaded' => 'Field :attribute gagal diunggah.',
    'url' => 'Format field :attribute tidak valid.',
    'uuid' => 'Field :attribute harus berupa UUID yang valid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        // Autentikasi
        'name' => 'Nama Lengkap',
        'email' => 'Alamat Email',
        'password' => 'Kata Sandi',
        'password_confirmation' => 'Konfirmasi Kata Sandi',
        'confirmPassword' => 'Konfirmasi Kata Sandi',
        'current_password' => 'Kata Sandi Saat Ini',
        'new_password' => 'Kata Sandi Baru',
        'new_password_confirmation' => 'Konfirmasi Kata Sandi Baru',
        'username' => 'Nama Pengguna',

        // Data Pribadi
        'nik' => 'Nomor NIK',
        'nama' => 'Nama',
        'nama_lengkap' => 'Nama Lengkap',
        'alamat' => 'Alamat',
        'telp' => 'Nomor Telepon',
        'phone' => 'Nomor Telepon',
        'rt' => 'RT',
        'rw' => 'RW',
        'gender' => 'Jenis Kelamin',
        'bio' => 'Bio',
        'address' => 'Alamat',
        'komunitas' => 'Komunitas',

        // Alamat Wilayah
        'id_provinsi' => 'Provinsi',
        'id_kota' => 'Kota/Kabupaten',
        'id_kecamatan' => 'Kecamatan',
        'id_desa' => 'Desa/Kelurahan',
        'province' => 'Provinsi',
        'city' => 'Kota',
        'district' => 'Kecamatan',
        'village' => 'Desa/Kelurahan',
        'postal_code' => 'Kode Pos',
        'country' => 'Negara',

        // Project
        'id_project' => 'Proyek',
        'id_Project' => 'Proyek',
        'NamaProjek' => 'Nama Proyek',
        'nama_projek' => 'Nama Proyek',
        'keterangan' => 'Keterangan',
        'deskripsi' => 'Deskripsi',

        // Produk
        'jenis_produk' => 'Jenis Produk',
        'jenisProduk' => 'Jenis Produk',
        'nama_produk' => 'Nama Produk',
        'namaProduk' => 'Nama Produk',
        'merk' => 'Merk',
        'tagline' => 'Tagline',
        'kelebihan' => 'Keunggulan',
        'gramasi' => 'Gramasi',
        'jenis_kemasan' => 'Jenis Kemasan',
        'jenisKemasan' => 'Jenis Kemasan',
        'segmentasi' => 'Segmentasi',
        'harga' => 'Harga',
        'varian' => 'Varian',
        'komposisi' => 'Komposisi',
        'redaksi' => 'Redaksi',
        'other' => 'Lainnya',
        'ukuran' => 'Ukuran',
        'kemasan' => 'Kemasan',
        'kapasitas' => 'Kapasitas',
        'tenaga_kerja' => 'Tenaga Kerja',
        'omzet' => 'Omzet',

        // Perizinan
        'namaUsaha' => 'Nama Usaha',
        'noNIB' => 'Nomor NIB',
        'noISO' => 'Nomor ISO',
        'noPIRT' => 'Nomor PIRT',
        'noHAKI' => 'Nomor HAKI',
        'noLayakSehat' => 'Nomor Surat Keterangan Layak Sehat',
        'noHalal' => 'Nomor Sertifikat Halal',
        'CPPOB' => 'CPPOB',
        'HACCP' => 'HACCP',
        'legalitasLain' => 'Legalitas Lainnya',
        'npwp' => 'NPWP',
        'siup' => 'SIUP',
        'tdp' => 'TDP',
        'akta_pendirian' => 'Akta Pendirian',
        'sku' => 'SKU',

        // File dan Media
        'gambar' => 'Gambar',
        'image' => 'Gambar',
        'logo' => 'Logo',
        'file' => 'File',
        'profile_photo' => 'Foto Profil',
        'croppedImage' => 'Gambar yang Dipotong',

        // Lainnya
        'title' => 'Judul',
        'content' => 'Konten',
        'description' => 'Deskripsi',
        'first_name' => 'Nama Depan',
        'last_name' => 'Nama Belakang',
        'kategori' => 'Kategori',
        'search' => 'Pencarian',
        'year' => 'Tahun',
        'ukm_count' => 'Jumlah UKM',
        'app_name' => 'Nama Aplikasi',
        'app_version' => 'Versi Aplikasi',
        'app_tagline' => 'Tagline Aplikasi',
        'company_name' => 'Nama Perusahaan',
        'copyright_text' => 'Teks Hak Cipta',
        'meta_description' => 'Meta Deskripsi',
        'meta_keywords' => 'Meta Kata Kunci',
        'registration_enabled' => 'Pendaftaran Diaktifkan',
        'width' => 'Lebar',
        'height' => 'Tinggi',
        'alignment' => 'Posisi',
        'position' => 'Posisi',
        'is_active' => 'Aktif',
        'custom_css' => 'CSS Kustom',
    ],
];
