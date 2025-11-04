@extends('layouts.app')
@section('content')
    @php
        $parent_title = 'Master';
        $page_title = 'Product';
    @endphp
    <div class="body-wrapper">
        <div class="container-fluid">

            <nav aria-label="breadcrumb" style="--bs-breadcrumb-divider: '&gt;'" class="mb-3">
                <ol class="breadcrumb bg-primary-subtle px-3 py-2 rounded">
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0)" class="text-primary d-flex align-items-center"><i
                                class="ti ti-home fs-4 mt-1"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0)" class="text-primary">{{ $parent_title }}</a>
                    </li>
                    <li class="breadcrumb-item active text-primary " aria-current="page">
                        <a href="{{ route('products.index') }}" class="text-primary">{{ $page_title }}</a>
                    </li>
                    <li class="breadcrumb-item active text-primary " aria-current="page">
                        Create
                    </li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Create Product</h4>
                    <form id="productForm" method="POST" action="{{ route('products.store') }}"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="row">

                            {{-- Name --}}
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="e.g. Aluminium Doors">
                                    <label for="name">Name</label>
                                </div>
                            </div>

                            {{-- Size --}}
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="size_mm" name="size_mm"
                                        placeholder="e.g. 250x300" required>
                                    <label for="size_mm">Size (mm)</label>
                                </div>
                            </div>


                            {{-- R/Units --}}
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" id="r_units" name="r_units" min="0">
                                    <label for="r_units">R/Units</label>
                                </div>
                            </div>

                            {{-- Product Price --}}
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" id="product_price" name="product_price"
                                        step="0.01" min="0">
                                    <label for="product_price">Product Price</label>
                                </div>
                            </div>

                            {{-- Image --}}
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="image" class="form-label fw-semibold">Product Image</label>
                                    <input type="file" name="image" id="image" class="form-control">
                                </div>
                            </div>

                            {{-- Descriptions --}}
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Description (Key - Value)</label>

                                <table class="table table-bordered align-middle" id="descriptionTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 40%">Key</th>
                                            <th style="width: 50%">Value</th>
                                            <th style="width: 10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="descBody">
                                        <tr>
                                            <td><input type="text" name="descriptions[0][key]" class="form-control"
                                                    placeholder="e.g. Brand"></td>
                                            <td><input type="text" name="descriptions[0][value]" class="form-control"
                                                    placeholder="e.g. Bosch"></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-primary btn-sm addRow">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24">
                                                        <path fill="currentColor"
                                                            d="M12 22c-4.714 0-7.071 0-8.536-1.465C2 19.072 2 16.714 2 12s0-7.071 1.464-8.536C4.93 2 7.286 2 12 2s7.071 0 8.535 1.464C22 4.93 22 7.286 22 12s0 7.071-1.465 8.535C19.072 22 16.714 22 12 22"
                                                            opacity="0.5" />
                                                        <path fill="currentColor"
                                                            d="M12 8.25a.75.75 0 0 1 .75.75v2.25H15a.75.75 0 0 1 0 1.5h-2.25V15a.75.75 0 0 1-1.5 0v-2.25H9a.75.75 0 0 1 0-1.5h2.25V9a.75.75 0 0 1 .75-.75" />
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>


                            <div class="col-12">
                                <div class="d-md-flex justify-content-between align-items-center">
                                    <a href="{{ route('products.index') }}" class="btn btn-light hstack gap-6">
                                        Back
                                    </a>
                                    <button type="submit" class="btn btn-primary hstack gap-6">
                                        Create
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            let rowIdx = 1;

            // Add new row
            $(document).on('click', '.addRow', function() {
                $('#descBody').append(`
                    <tr>
                        <td><input type="text" name="descriptions[${rowIdx}][key]" class="form-control" placeholder="e.g. Color"></td>
                        <td><input type="text" name="descriptions[${rowIdx}][value]" class="form-control" placeholder="e.g. Red"></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm removeRow"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M2.75 6.167c0-.46.345-.834.771-.834h2.665c.529-.015.996-.378 1.176-.916l.03-.095l.115-.372c.07-.228.131-.427.217-.605c.338-.702.964-1.189 1.687-1.314c.184-.031.377-.031.6-.031h3.478c.223 0 .417 0 .6.031c.723.125 1.35.612 1.687 1.314c.086.178.147.377.217.605l.115.372l.03.095c.18.538.74.902 1.27.916h2.57c.427 0 .772.373.772.834S20.405 7 19.979 7H3.52c-.426 0-.771-.373-.771-.833"/><path fill="currentColor" d="M11.607 22h.787c2.707 0 4.06 0 4.941-.863c.88-.864.97-2.28 1.15-5.111l.26-4.081c.098-1.537.147-2.305-.295-2.792s-1.187-.487-2.679-.487H8.23c-1.491 0-2.237 0-2.679.487s-.392 1.255-.295 2.792l.26 4.08c.18 2.833.27 4.248 1.15 5.112S8.9 22 11.607 22" opacity="0.5"/></svg></button>
                        </td>
                    </tr>
                `);
                rowIdx++;
            });

            // Remove row
            $(document).on('click', '.removeRow', function() {
                $(this).closest('tr').remove();
            });

            // Optional: Ajax submission (prevent full reload)
            // $('#productForm').on('submit', function(e) {
            //     e.preventDefault();
            //     const formData = new FormData(this);

            //     $.ajax({
            //         url: $(this).attr('action'),
            //         type: 'POST',
            //         data: formData,
            //         processData: false,
            //         contentType: false,
            //         success: function(res) {
            //             if (res.status) {
            //                 alert(res.message);
            //                 $('#productForm')[0].reset();
            //                 $('#descBody').html(`
            //             <tr>
            //                 <td><input type="text" name="descriptions[0][key]" class="form-control"></td>
            //                 <td><input type="text" name="descriptions[0][value]" class="form-control"></td>
            //                 <td class="text-center">
            //                     <button type="button" class="btn btn-primary btn-sm addRow"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12 22c-4.714 0-7.071 0-8.536-1.465C2 19.072 2 16.714 2 12s0-7.071 1.464-8.536C4.93 2 7.286 2 12 2s7.071 0 8.535 1.464C22 4.93 22 7.286 22 12s0 7.071-1.465 8.535C19.072 22 16.714 22 12 22" opacity="0.5"/><path fill="currentColor" d="M12 8.25a.75.75 0 0 1 .75.75v2.25H15a.75.75 0 0 1 0 1.5h-2.25V15a.75.75 0 0 1-1.5 0v-2.25H9a.75.75 0 0 1 0-1.5h2.25V9a.75.75 0 0 1 .75-.75"/></svg></button>
            //                 </td>
            //             </tr>
            //         `);
            //             }
            //         },
            //         error: function(xhr) {
            //             console.error(xhr.responseText);
            //             alert('Something went wrong 🫠');
            //         }
            //     });
            // });
        });
    </script>
@endsection
