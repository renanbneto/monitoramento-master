@extends('adminlte::page')

@section('title', 'LPR')

@section('content_header')
@stop

@section('content')
   
<div class="row p-3">
    <div class="col-12">
        <div id="map" style="height: 600px; width: 100%;"></div>
    </div>
    <div class="col-12 mt-3">
        <h3 class="rounded bg-info text-center">Intenções de câmeras cadastradas</h3>
        <table id="camerasLPR" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Ações</th>
                    <th>Identificação</th>
                    <th>Solicitante</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Endereço</th>
                    <th>Sentido</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para cadastro de câmera -->
<div class="modal fade" id="cameraModal" tabindex="-1" role="dialog" aria-labelledby="cameraModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cameraModalLabel">Cadastrar câmera</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <p><strong>Latitude:</strong> <span id="modal-lat"></span></p>
        <p><strong>Longitude:</strong> <span id="modal-lng"></span></p>
        <p><strong>Endereço:</strong> <span id="modal-address"></span></p>
        <button class="btn btn-primary" id="btn-cadastrar-camera">Cadastrar câmera aqui</button>
      </div>
    </div>
  </div>
</div>

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.0/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/buttons/2.2.0/css/buttons.dataTables.min.css">

        <style>
            .dt-button.buttons-csv {}
                background-color: #28a745 !important;
                color: #fff !important;
                border: none !important;
                padding-left: 2.5em !important;
                position: relative;
            }

            .dt-button.buttons-csv:before {
                content: '';
                display: inline-block;
                background-image: url('data:image/svg+xml;utf8,<svg fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" fill="%2328a745"/><path d="M8 17v-6h1.5v6H8zm3.25 0v-6h1.5v4.25L15.5 11h1.75l-2.25 2.5 2.25 3.5h-1.75l-1.75-2.75V17h-1.5z" fill="white"/><text x="6" y="19" font-size="7" fill="white" font-family="Arial">CSV</text></svg>');
                background-size: 1.2em 1.2em;
                width: 1.2em;
                height: 1.2em;
                position: absolute;
                left: 1em;
                top: 50%;
                transform: translateY(-50%);
                content: '';
            }
        </style>
@endsection

@push('js')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap4.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
{{-- <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js">
</script>
<script type="text/javascript" charset="utf8"
    src="https://cdn.datatables.net/buttons/2.2.0/js/dataTables.buttons.min.js"></script>


<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js">
</script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.0/js/buttons.html5.min.js">
</script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.0/js/buttons.print.min.js">
</script>
<script>

    // Limites aproximados do estado do Paraná
    const paranaBounds = [
        [-26.7235, -54.6176], // Sudoeste
        [-22.4899, -48.0126]  // Nordeste
    ];
    const paranaCenter = [-24.4842, -51.8626];

    var map = L.map('map', {
        center: paranaCenter,
        zoom: 7,
        maxBounds: paranaBounds,
        maxBoundsViscosity: 1.0
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Layer para os pins das câmeras
    let camerasLayer = L.layerGroup().addTo(map);

    // Adiciona os markers mockados ao layer de câmeras
    var camIcon = L.icon({
                    iconUrl:'{{asset('images/cam.png')}}',
                    iconSize: [40, 40],
                    iconAnchor: [20, 40]
                });

    // Função para carregar e exibir as câmeras no mapa
    function loadCamerasOnMap(data) {

        console.log(data);
        
        
        camerasLayer.clearLayers();

        data.forEach(function(camera) {
                if (camera.lat && camera.lng) {
                    let marker = L.marker([camera.lat, camera.lng],{icon:camIcon})
                        .bindPopup(`<strong>${camera.nome}</strong><br>${camera.endereco || ''}<br>Sentido: ${camera.sentido || ''}`);
                    camerasLayer.addLayer(marker);
                }
            });

        /* mockCameras.forEach(function(camera) {
        if (camera.latitude && camera.longitude) {
            let marker = L.marker([camera.latitude, camera.longitude])
                .bindPopup(`<strong>${camera.nome}</strong><br>${camera.endereco}<br>Sentido: ${camera.sentido}`);
            camerasLayer.addLayer(marker);
        }
        }); */
    }

    // Carregar câmeras ao inicializar DataTable
    $('#camerasLPR').on('xhr.dt', function(e, settings, json, xhr) {
        loadCamerasOnMap(json.data);
    });

    // Recarregar câmeras após cadastrar nova câmera
    $('#btn-cadastrar-camera').on('click', function() {
        // ... lógica de cadastro ...
        //setTimeout(loadCamerasOnMap, 1000); // Ajuste conforme a resposta do backend
    });
    

    

    // Exemplo de pins de câmeras (adicione dinamicamente conforme necessário)
    // L.marker([-25.4284, -49.2733]).addTo(map).bindPopup('Câmera 1 - Curitiba');

    function reverseGeocode(lat, lng, callback) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.address) {
                    const addr = data.address;
                    // Extrai apenas as partes relevantes
                    const numero = addr.house_number || '';
                    const logradouro = addr.road || addr.pedestrian || addr.footway || addr.cycleway || addr.path || '';
                    const bairro = addr.suburb || addr.neighbourhood || '';
                    const cidade = addr.city || addr.town || addr.village || '';
                    // Monta string final: 45, Avenida..., Bairro, Cidade
                    let partes = [];
                    if (numero && logradouro) {
                        partes.push(`${numero}, ${logradouro}`);
                    } else if (logradouro) {
                        partes.push(logradouro);
                    }
                    if (bairro) partes.push(bairro);
                    if (cidade) partes.push(cidade);
                    callback(partes.join(', '));
                } else {
                    callback('Endereço não encontrado');
                }
            })
            .catch(() => callback('Endereço não encontrado'));
    }

    map.on('click', function(e) {
        var lat = e.latlng.lat.toFixed(6);
        var lng = e.latlng.lng.toFixed(6);

        reverseGeocode(lat, lng, function(address) {
            $('#modal-lat').text(lat);
            $('#modal-lng').text(lng);
            $('#modal-address').text(address);
            $('#cameraModal').modal('show');
        });
    });

    $('#btn-cadastrar-camera').on('click', function() {
        // Aqui você pode implementar a lógica para cadastrar a câmera
        // Extrai os dados do modal já preenchidos
        const lat = $('#modal-lat').text();
        const lng = $('#modal-lng').text();
        const enderecoCompleto = $('#modal-address').text();

        // Extrai cidade e bairro do endereço (usando regex simples)
        let cidade = '', bairro = '';
        const matchCidade = enderecoCompleto.match(/, ([^,]+), PR, /);
        if (matchCidade) cidade = matchCidade[1];

        const matchBairro = enderecoCompleto.match(/, ([^,]+) - [^,]+, PR, /);
        if (matchBairro) bairro = matchBairro[1];

        Swal.fire({
            title: 'Cadastrar Câmera',
            html: `
            <form id="form-cadastro-camera">
                <div class="form-group text-left">
                <label>Nome</label>
                <input type="text" class="form-control" name="nome" required>
                </div>
                <div class="form-group text-left">
                <label>Latitude</label>
                <input type="text" class="form-control" name="latitude" value="${lat}" readonly>
                </div>
                <div class="form-group text-left">
                <label>Longitude</label>
                <input type="text" class="form-control" name="longitude" value="${lng}" readonly>
                </div>
                <div class="form-group text-left">
                <label>Endereço</label>
                <input type="text" class="form-control" name="endereco" value="${enderecoCompleto}">
                </div>
                <div class="form-group text-left">
                <label>Bairro</label>
                <input type="text" class="form-control" name="bairro" value="${bairro}">
                </div>
                <div class="form-group text-left">
                <label>Cidade</label>
                <input type="text" class="form-control" name="cidade" value="${cidade}">
                </div>
                <div class="form-group text-left">
                <label>Sentido</label>
                <input type="text" class="form-control" name="sentido">
                </div>
            </form>
            `,
            showCancelButton: true,
            confirmButtonText: 'Salvar',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {
            const form = $('#form-cadastro-camera');
            return {
                nome: form.find('[name="nome"]').val(),
                lat: form.find('[name="latitude"]').val(),
                lng: form.find('[name="longitude"]').val(),
                endereco: form.find('[name="endereco"]').val(),
                bairro: form.find('[name="bairro"]').val(),
                cidade: form.find('[name="cidade"]').val(),
                sentido: form.find('[name="sentido"]').val()
            };
            }
        }).then((result) => {
            if (result.isConfirmed) {
            $.ajax({
                url: '{{ route('prospeccoesLPR.store') }}',
                type: 'POST',
                data: result.value,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire('Câmera cadastrada!', '', 'success');
                    $('#camerasLPR').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    let msg = 'Erro ao cadastrar câmera.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    Swal.fire('Erro', msg, 'error');
                }
            });
            Swal.fire('Câmera cadastrada!', '', 'success');
            }
        });
        $('#cameraModal').modal('hide');
    });


    $(document).ready(function() {

        window.excluirCamera = function(element) {
            const id = $(element).data('id');
            Swal.fire({
                title: 'Tem certeza?',
                text: 'Deseja realmente excluir esta câmera?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim, excluir',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("prospeccoesLPR.destroy",":id") }}'.replace(":id",id),
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire('Excluída!', 'A câmera foi excluída.', 'success');
                            $('#camerasLPR').DataTable().ajax.reload();
                        },
                        error: function(xhr) {
                            let msg = 'Erro ao excluir câmera.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            }
                            Swal.fire('Erro', msg, 'error');
                        }
                    });
                }
            });
        };

        $('#camerasLPR').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('prospeccoesLPR.index') }}',
            columns: [
            { data: 'acoes', name: 'acoes', orderable: false, searchable: false },
            { data: 'nome', name: 'nome' },
            { data: 'cadastrada_por', name: 'cadastrada_por' },
            { data: 'lat', name: 'lat' },
            { data: 'lng', name: 'lng' },
            { data: 'endereco', name: 'endereco' },
            { data: 'sentido', name: 'sentido' },
            ],
            language: {
            url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json"
            },
            responsive: true,
            autoWidth: false,
            scrollX: true,
            dom:
    "<'row align-items-center mb-2'<'col-md-3'l><'col-md-3'B><'col-md-6'f>>" +
    "<'row'<'col-sm-12'tr>>" +
    "<'row mt-2'<'col-sm-5'i><'col-sm-7'p>>",
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
            buttons: [
            {
                extend: 'csv',
                text: 'Exportar CSV',
                className: 'btn btn-success'
            }
            ]
        });

        // Garante overflow horizontal se necessário
        $('#camerasLPR').parent().css({
            'overflow-x': 'auto',
            'width': '100%'
        });
    });
</script>
@endpush

@stop
