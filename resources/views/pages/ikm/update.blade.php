@extends('layouts.master')

@section('page-title', 'Update Ikm - ' . ($project->NamaProjek ?? 'Edit IKM'))
@section('content')
   <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.css" rel="stylesheet">
<style>.select2-container--open {
    z-index: 9999999
      }
     .select2.select2-container {
         width: 100% !important;
     }

     .select2.select2-container .select2-selection {
         font-family: "Nunito Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
         font-size: 12px;
         border: 1px solid #cbd0dd;
         -webkit-border-radius: 3px;
         -moz-border-radius: 3px;
         border-radius: 0.375rem;
         height: 36px;
         padding: 2px;
         margin-bottom: 15px;
         outline: none !important;
         transition: all .15s ease-in-out;
     }

     .select2.select2-container .select2-selection .select2-selection__rendered {
         font-weight: 600;
         color: #3b3b3b;
         line-height: 32px;
         padding-right: 33px;
     }

     .select2.select2-container .select2-selection .select2-selection__arrow {
         background: #f8f8f8;

         border-left: 1px solid #ccc;
         -webkit-border-radius: 0 3px 3px 0;
         -moz-border-radius: 0 3px 3px 0;
         border-radius: 0 3px 3px 0;
         height: 32px;
         width: 33px;
     }

     .select2.select2-container.select2-container--open .select2-selection.select2-selection--single {
         background: #f8f8f8;
     }

     .select2.select2-container.select2-container--open .select2-selection.select2-selection--single .select2-selection__arrow {
         -webkit-border-radius: 0 3px 0 0;
         -moz-border-radius: 0 3px 0 0;
         border-radius: 0 3px 0 0;
     }

     .select2.select2-container.select2-container--open .select2-selection.select2-selection--multiple {
         border: 1px solid #34495e;
     }

     .select2.select2-container .select2-selection--multiple {
         height: auto;
         min-height: 34px;
     }

     .select2.select2-container .select2-selection--multiple .select2-search--inline .select2-search__field {
         margin-top: 0;
         height: 32px;
     }

     .select2.select2-container .select2-selection--multiple .select2-selection__rendered {
         display: block;
         padding: 0 4px;
         line-height: 29px;
     }

     .select2.select2-container .select2-selection--multiple .select2-selection__choice {
         background-color: #f8f8f8;
         border: 1px solid #ccc;
         -webkit-border-radius: 3px;
         -moz-border-radius: 3px;
         border-radius: 3px;
         margin: 4px 4px 0 0;
         padding: 0 6px 0 22px;
         height: 24px;
         line-height: 24px;
         font-size: 12px;
         position: relative;
     }

     .select2.select2-container .select2-selection--multiple .select2-selection__choice .select2-selection__choice__remove {
         position: absolute;
         top: 0;
         left: 0;
         height: 22px;
         width: 22px;
         margin: 0;
         text-align: center;
         color: #e74c3c;
         font-weight: bold;
         font-size: 16px;
     }

     .select2-container .select2-dropdown {
         background: transparent;
         border: none;
         margin-top: -5px;
     }

     .select2-container .select2-dropdown .select2-search {
         padding: 0;
     }

     .select2-container .select2-dropdown .select2-search input {
         outline: none !important;
         border: 1px solid #aab2ca !important;
         border-bottom: none !important;
         padding: 4px 6px !important;
     }

     .select2-container .select2-dropdown .select2-results {
         padding: 0;
     }

     .select2-container .select2-dropdown .select2-results ul {
         background: #fff;
         border: 1px solid #aab2ca;
     }

     .select2-container .select2-dropdown .select2-results ul .select2-results__option--highlighted[aria-selected] {
         background-color: #3498db;
     }</style>
   @foreach ( $dataIkm as $a)
<form action="{{ route('ikm.update', $a->id) }}" method="POST">
    @csrf

    <div class="row justify-content-between align-items-end g-3 ">
        <div class="col-12 col-sm-auto col-xl-8">
            <h2>Update Ikm</h2>
        </div>
        <div class="col-12 col-sm-auto col-xl-4">
            <div class="d-flex"><a href="/project/dataIkm/{{ $project->id }}" class="btn btn-phoenix-primary px-5 me-2">Batal</a>
                <button type="submit" class="btn btn-primary px-5 w-100 text-nowrap">Simpan</button>
            </div>
        </div>
    </div>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="py-1 mb-5">
        <ul class="nav nav-underline" id="myTab" role="tablist">
            <li class="nav-item" role="presentation"><a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#tab-Updateinfo" role="tab" aria-controls="tab-home" aria-selected="false" tabindex="-1"><i data-feather="user"></i> Infomasi
                    Ikm</a></li>
            <li class="nav-item" role="presentation"><a class="nav-link " id="home-tab" data-bs-toggle="tab" href="#tab-Updatehome" role="tab" aria-controls="tab-Updatehome" aria-selected="false" tabindex="-1"><i data-feather="box"></i> Infomasi
                    Product</a></li>
            <!--<li class="nav-item" role="presentation"><a class="nav-link" id="profile-tab" data-bs-toggle="tab"-->
            <!--        href="#tab-Updateprofile" role="tab" aria-controls="tab-profile" aria-selected="false"-->
            <!--        tabindex="-1"><i data-feather="command"></i> Legalitas-->
            <!--        / Informasi</a></li>-->

        </ul>
        <div class="tab-content mt-3" id="myTabContent">
            <div class="tab-pane fade  active show" id="tab-Updateinfo" role="tabpanel" aria-labby="home-tab">
                <div id="London" class="tabcontent" style="display: block;">

                    <div class="section1">
                        {{-- variabel id Ikm --}}
                        <input type="text" value="{{ $a->id }}" name="id_Ikm" hidden>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label" for="provinsi">Nama Lengkap<span style="color:red">*</span></label>
                                <input class="form-control @error('nama') is-invalid @enderror" id="nama" type="text" placeholder="Nama Lengkap" name="nama" value="{{ $a->nama }}" />
                                @error('nama')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror

                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="name">No Telepon<span style="color:red">*</span></label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text" id="addon-wrapping">
                                        <span class="ti ti-phone" width="15"></span>
                                    </span>
                                    <input class="form-control @error('telp') is-invalid @enderror" type="text " placeholder="Nomor Telepon" name="telp" id="telp" value="{{ $a->telp }}" />
                                </div>
                                @error('telp')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                        </div>

                        <div class="row g-3 mb-3">

                            <div class="col-md-6">
                                <label class="form-label" for="email">Jenis Kelamin<span style="color:red">*</span></label>
                                <select class="form-control select2 @error('gender') is-invalid @enderror" aria-label="Default select example" name="gender">
                                    {{-- <option value="">-Pilih Gender-</option> --}}
                                    @if ($a->gender==1)
                                    <option value="1">Laki - Laki</option>
                                    @else
                                    <option value="2">Perempuan</option>
                                    @endif
                                    <option value="1">Laki - Laki</option>
                                    <option value="2">Perempuan</option>
                                </select>
                                @error('gender')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="id_Project" class="form-label"> Asosiasi / Komunitas </label>
                                <select name="id_Project" id="" class="form-control" disabled>
                                    <option value="{{ $project->id }}">{{ $project->NamaProjek }}</option>
                                </select>
                            </div>
                        </div>


                    </div>
                    {{-- section 2 --}}
                    <div class="section2">
                        <div class="mb-3 text-start">
                            <label class="form-label" for="alamat">Alamat<span style="color:red">*</span></label>
                            <input class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" type="text" placeholder="Alamat" value="{{ $a->alamat }}" />
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label" for="provinsi">Provinsi<span style="color:red">*</span></label>
                                <select class="form-control select2 js-example-basic-single-Update @error('provinsi') is-invalid @enderror" id="provinsiUpdate" name="id_provinsi">
                                    <option value="
                                    @if ($a->province !="")
                                        {{ $a->province->id }}
                                    @endif
                                    ">
                                        @if ($a->province != "")
                                        {{ $a->province->name }}
                                        @endif

                                    </option>

                                    @foreach ($provinsi as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>

                                @error('provinsi')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="kabupaten">Kota/Kabupaten<span style="color:red">*</span></label>
                                <select id="kabupatenUpdate" name="id_kota" class="select2 form-control js-example-basic-single-Update @error('kota') is-invalid @enderror">
                                    <option value="
                                 @if ($a->regency != "")
                                    {{ $a->regency->id }}
                                 @endif

                                 ">
                                        @if ($a->regency != "")
                                        {{ $a->regency->name}}
                                        @endif
                                    </option>

                                </select>
                                @error('kota')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <label class="form-label" for="kecamatan">Kecamatan<span style="color:red">*</span></label>
                                <select class="form-select select2 js-example-basic-single-Update @error('kecamatan') is-invalid @enderror" id="kecamatanUpdate" name="id_kecamatan">
                                    <option value="
                                    @if ($a->district != "")
                                        {{ $a->district->id }}
                                    @endif ">
                                        @if ($a->district != "" )
                                        {{ $a->district->name }}
                                        @endif

                                    </option>
                                </select>
                                @error('kecamatan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="desa">Kelurahan/Desa<span style="color:red">*</span></label>
                                <select class="form-select select2 js-example-basic-single-Update @error('desa') is-invalid @enderror" id="desaUpdate" name="id_desa">

                                    <option value="
                                    @if ($a->village != "")
                                    {{ $a->village->id }}
                                    @endif
                                    " selected>
                                        @if ($a->village != "")
                                        {{ $a->village->name }}
                                        @endif
                                    </option>

                                </select>
                                @error('desa')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row g-3 mb2">
                            <div class="col-md-6">
                                <div class="mb-3 text-start">
                                    <label class="form-label" for="rt">RT<span style="color:red">*</span></label>
                                    <input class="form-control @error('rt') is-invalid @enderror" id="rt" name="rt" type="text" placeholder="RT" value="{{ $a->rt }}" />
                                    @error('rt')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 text-start">
                                    <label class="form-label" for="rw">RW<span style="color:red">*</span></label>
                                    <input class="form-control @error('rw') is-invalid @enderror" id="rw" name="rw" type="text" placeholder="RW" value="{{ $a->rw }}" />
                                </div>
                                @error('rw')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <button id="next1Update" class="btn btn-primary">Tahap Selanjutnya</button>
                    </div>

                </div>
            </div>
        </div>

        {{-- --}}


<div class="tab-content mt-3" id="myTabContent">
<div class="tab-pane fade show active" id="tab-Updatehome">

<div class="row">

<div class="col-lg-6">
<label>Jenis Produk</label>
<input class="form-control" type="text" name="jenisProduk" value="{{ $a->jenisProduk }}">
</div>

<div class="col-lg-6">
<label>Merk</label>
<textarea class="form-control editor" name="merk">{!! $a->merk !!}</textarea>
</div>

<div class="col-lg-6">
<label>Tagline</label>
<textarea class="form-control editor" name="tagline">{!! $a->tagline !!}</textarea>
</div>

<div class="col-lg-6">
<label>Kelebihan Produk</label>
<textarea class="form-control editor" name="kelebihan">{!! $a->kelebihan !!}</textarea>
</div>

<div class="col-lg-6">
<label>Gramasi (gram)</label>
<input type="text" class="form-control" name="gramasi" value="{{ $a->gramasi }}">
</div>

<div class="col-lg-6">
<label>Jenis Kemasan</label>
<textarea class="form-control editor" name="jenisKemasan">{!! $a->jenisKemasan !!}</textarea>
</div>

<div class="col-lg-6">
<label>Segmentasi Produk</label>
<textarea class="form-control editor" name="segmentasi">{!! $a->segmentasi !!}</textarea>
</div>

<div class="col-lg-6">
<label>Kemasan Pendukung</label>
<textarea class="form-control editor" name="harga">{!! $a->harga !!}</textarea>
</div>

<div class="col-lg-6">
<label>Varian Produk</label>
<textarea class="form-control editor" name="varian">{!! $a->varian !!}</textarea>
</div>

<div class="col-lg-6">
<label>Komposisi Produk</label>
<textarea class="form-control editor" name="komposisi">{!! $a->komposisi !!}</textarea>
</div>

<div class="col-lg-12">
<label>Redaksi Produk</label>
<textarea class="form-control editor" name="redaksi">{!! $a->redaksi !!}</textarea>
</div>

<div class="col-lg-12">
<label>Keterangan Lainnya</label>
<textarea class="form-control editor" name="other">{!! $a->other !!}</textarea>
</div>

<div class="col-lg-12">
<label>Nama Perusahaan</label>
<textarea class="form-control editor" name="namaUsaha">{!! $a->namaUsaha !!}</textarea>
</div>

<div class="col-lg-6">
<label>Nomor SP-IRT</label>
<textarea class="form-control editor" name="noPIRT">{!! $a->noPIRT !!}</textarea>
</div>

<div class="col-lg-6">
<label>Halal</label>
<textarea class="form-control editor" name="noHalal">{!! $a->noHalal !!}</textarea>
</div>

<div class="col-lg-12">
<label>Legalitas Lainnya</label>
<textarea class="form-control editor" name="legalitasLain">{!! $a->legalitasLain !!}</textarea>
</div>

<input type="hidden" name="id_Project" value="{{ $project->id }}">

<div class="col-lg-12 mt-3">
<button type="submit" class="btn btn-primary">Simpan Data</button>
</div>

</div>
</div>
</div>


    </div>
</form>
@endforeach
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<!-- Bootstrap -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.js"></script>

<script>
$(document).ready(function() {
    $('.editor').summernote({
        height: 180,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['fontsize']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link']],
            ['view', ['codeview']]
        ]
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(function(){
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });

        $(function (){
            $('#provinsiUpdate').on('change',function(){
                let id_provinsi = $('#provinsiUpdate').val();

                $.ajax({
                    type : 'POST',
                    url : '/getkabupatenUpdate',
                    data : {id_provinsi : id_provinsi},
                    cache : false,

                    success: function(msg){
                        $('#kabupatenUpdate').html(msg);
                        $('#kecamatanUpdate').html('<option value=""> Kecamatan </option>');
                        $('#desaUpdate').html('<option value=""> Kelurahan/Desa </option>');
                    },
                    error: function(data){
                        console.log('error:',data);
                    }
                })
            })
        })

        $(function (){
            $('#kabupatenUpdate').on('change',function(){
                let id_kabupaten = $('#kabupatenUpdate').val();

                $.ajax({
                    type : 'POST',
                    url : '/getkecamatanUpdate',
                    data : {id_kabupaten : id_kabupaten},
                    cache : false,

                    success: function(msg){
                        $('#kecamatanUpdate').html(msg);
                        $('#desaUpdate').html('<option value=""> Kelurahan/Desa </option>');
                    },
                    error: function(data){
                        console.log('error:',data);
                    }
                })
            })
        })
        $(function (){
            $('#kecamatanUpdate').on('change',function(){
                let id_kecamatan = $('#kecamatanUpdate').val();

                $.ajax({
                    type : 'POST',
                    url : '/getdesaUpdate',
                    data : {id_kecamatan : id_kecamatan},
                    cache : false,

                    success: function(msg){
                        $('#desaUpdate').html(msg);
                    },
                    error: function(data){
                        console.log('error:',data);
                    }
                })
            })
        })

        // Next button handler for Update form
        $('#next1Update').on('click', function(e) {
            e.preventDefault();
            // Switch to the Product tab
            $('#tab-Updatehome').addClass('active show');
            $('#tab-Updateinfo').removeClass('active show');
            // Update nav tabs
            $('a[href="#tab-Updatehome"]').tab('show');
        });

        // Tab change handler
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
            // Refresh Summernote when switching tabs
            if ($($(e.target).attr('href')).find('.editor').length > 0) {
                $('.editor').summernote('refresh');
            }
        });
    });
</script>
@endsection
