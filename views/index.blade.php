<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

@include('modals')


<div class="container-fluid align-items-center-align-self-center">
    <div class="my-3 d-flex flex-row justify-content-between">
        <div class="form-group p-0 m-0">
            <input type="button" onclick="pingAll()" class="btn btn-primary"
                value="{{ __("IP'leri Kontrol Et") }}">
            <input type="button" onclick="$('#addIpModal').modal('show')" class="btn btn-primary" value="IP Ekle">
        </div>
        <div class="form-group p-0 m-0">
            <input type="button" onclick="createServerStatusTable()" class="btn btn-primary" value="{{ __("Durum Tablosu") }}">
        </div>
    </div>


    <div class="text-center my-3 pb-3">
        <h1>IP Listesi</h1>
    </div>
    
    <div id="ips" class="card container-fluid card-deck my-3" style="min-height: 50vh">

    </div>


</div>

@include('scripts')