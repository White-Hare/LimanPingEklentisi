<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

@include('modals')

<div class="container-fluid align-items-center-align-self-center">
    <div class="my-3 d-flex flex-row justify-content-between">
        <div class="form-group p-0 m-0 row">
            <div class="col">
                <input type="button" onclick="pingForTable()" class="btn btn-primary"
                    value="{{ __("IP'leri Kontrol Et") }}">
            </div>

            <div class="col">
                <select id="timeInterval" class="form-control">
                    <option value="-1">Yenileme</option>
                    <option value="10000">10s</option>
                    <option value="30000">30s</option>
                    <option value="60000">1dk</option>
                    <option value="120000">2dk</option>
                </select>
            </div>
        </div>
        <div class="form-group p-0 m-0">
            <input type="button" onclick="getSavedIps()" class="btn btn-primary" value="{{ __('Duzenle') }}">
        </div>
    </div>

    <div id="serverStatusTable"></div>

</div>
@include('scripts')
