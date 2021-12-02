<script>
    function ping(ip) {
        let data = new FormData();
        data.append("ip", ip);

        request("{{ API('ping') }}", data, function(response) {
            response = JSON.parse(response).message;


            $("#ips .ipNamePair #ipDiv").each((i, ip) => {
                if ($(ip).find("#ip").val() == response.ip) {
                    let $icon = $(ip).next().find('span');
                    $icon.removeClass();


                    if (response.result == true)
                        $icon.addClass('fas fa-check text-success');
                    else
                        $icon.addClass('fas fa-times text-danger');
                }
            });

            Swal.close();


        }, function(response) {
            response = JSON.parse(response);
            console.log(response);
            showSwal(response.message, 'error');
        });
    }


    function pingAll() {
        showSwal('{{ __('Yükleniyor...') }}', 'info');
        let ips = [];

        $("#ips .ipNamePair #ip").each((i, ip) => {
            ips.push($(ip).val());
        });

        $("#ips .ipNamePair #iconDiv").each((i, ip) => {
            let $icon = $(ip).find('span');
            $icon.removeClass();
            $icon.addClass("fas fa-circle-notch fa-spin");
        });

        for (let ip of ips) {
            ping(ip)
        }
    }


    function pingForTable() {
        $("#serverStatusTable table tbody tr").each((i, tr) => {
            let $ip = $(tr).find("#ip");
            let $status = $(tr).find("#status");

            $status.html('<span class="fas fa-circle-notch fa-spin"></span>');

            let ip = $ip.text();

            let data = new FormData();
            data.append("ip", ip);

            request("{{ API('ping') }}", data, function(response) {
                response = JSON.parse(response).message;


                if (ip == response.ip) {
                    if (response.result == true)
                        $status.html('<span class="fas fa-check text-success"></span>');
                    else
                        $status.html('<span class="fas fa-times text-danger"></span>');
                }

                Swal.close();


            }, function(response) {
                response = JSON.parse(response);
                console.log(response);
                showSwal(response.message, 'error');
            });
        });
    }


    function getSavedIps() {
        showSwal('{{ __('Yükleniyor...') }}', 'info');
        let data = new FormData();
        request("{{ API('get_saved_ips') }}", data, function(response) {
            response = JSON.parse(response).message;

            let finalHtml = "";
            for (const ip of response) {
                let name = ip.name ? ip.name : ip.ip;

                finalHtml +=
                    /*html*/
                    `
                        <div id="ipNamePair-${ip.id}" class="p-3 col-lg-3 col-md-4">
                            <div class="card card-body d-flex flex-column ipNamePair">
                                <input type="hidden" id="id" value="${ip.id}"/>
                                <div id="nameDiv" class="row my-1">
                                    <div class="col-2 align-self-center align-items-center"><label for="name">Isim</label></div>
                                    <div class="col-10"><input id="name" class="form-control listValueKeyPairInput" type="text" value="${name}"/></div>
                                </div>
                                <div id="ipDiv" class="row my-1">
                                    <div class="col-2 align-self-center align-items-center"><label for="ip">Ip</label></div>
                                    <div class="col-10"><input id="ip" class="form-control listValueKeyPairInput" type="text" value="${ip.ip}"/></div>
                                </div>
                                <div id="iconDiv" class="row my-1 align-self-center"><span class="fas fa-times text-danger" style="font-size:2em"></span></div>
                                <div class="row my-1 align-self-center align-items-center">
                                    <div class="col">
                                        <input type="button" class="btn btn-success" value="Güncelle" onclick="updateIp('${ip.id}')">
                                    </div>
                                    <div class="col">
                                        <input type="button" class="btn btn-danger" value="Sil" onclick="deleteIp('${ip.id}')">
                                    </div>
                                    <!--<div class="col">
                                        <input type="button" class="btn btn-primary" value="Ping" onclick="ping('${ip.ip}')">
                                    </div>-->
                                </div>
                            </div>
                        </div>
                    `;
            }

            $("#ips").html(finalHtml);


            pingAll();
            Swal.close();


        }, function(response) {
            response = JSON.parse(response);
            console.log(response);
            showSwal(response.message, 'error');
        });
    }


    function addIp() {
        showSwal('{{ __('Yükleniyor...') }}', 'info');
        let data = new FormData();

        $name = $("#addIpName");
        $ip = $("#addIpIp");

        data.append('name', $name.val());
        data.append('ip', $ip.val());


        request("{{ API('add_ip') }}", data, function(response) {
            $ip.val('');
            $name.val('');

            $("#ipAddStatus").html(
                /*html*/
                `<div id="ipAddStatus" class="text-success">IP Eklendi</div>`
            );


            getSavedIps();
        }, function(response) {
            response = JSON.parse(response);
            console.log(response);

            $("#ipAddStatus").html(
                /*html*/
                `<div id="ipAddStatus" class="text-danger">Bir Sorun Olustu</div>`
            );

            showSwal(response.message, 'error');
        });
    }


    function deleteIp(id) {
        showSwal('{{ __('Yükleniyor...') }}', 'info');

        let data = new FormData();
        data.append('id', id);

        request("{{ API('delete_ip') }}", data, function(response) {
            getSavedIps();

        }, function(response) {
            response = JSON.parse(response);
            console.log(response);
            showSwal(response.message, 'error');
        });
    }


    function updateIp(id, name, ip) {

        showSwal('{{ __('Yükleniyor...') }}', 'info');

        name = $(`#ipNamePair-${id}`).find("#name").val();
        ip = $(`#ipNamePair-${id}`).find("#ip").val();

        let data = new FormData();
        data.append('id', id);
        data.append('name', name);
        data.append('ip', ip);

        request("{{ API('update_ip') }}", data, function(response) {
            getSavedIps();

        }, function(response) {
            response = JSON.parse(response);
            console.log(response);
            showSwal(response.message, 'error');
        });
    }


    function createServerStatusTable() {

        showSwal('{{ __('Yükleniyor...') }}', 'info');


        let data = new FormData();
        const formatColumn = function(data, row, column, node) {
            // Strip $ from salary column to make it numeric
            if (column !== 0)
                return data



            return $(node).find('span').hasClass('text-success') ? 'Aktif' : 'Ulasilamadi';
        }



        request("{{ API('get_saved_ips_table') }}", data, function(response) {
                $("#serverStatusTable").html(response).find('table').DataTable({
                    dom: 'Bfrtip',
                    buttons: [{
                            text: "{{ __('Kopyala') }}",
                            extend: 'copy',
                            exportOptions: {
                                columns: [1, 2, 3],
                                format: {
                                    body: formatColumn
                                }
                            }
                        },
                        {
                            extend: 'csv',
                            exportOptions: {
                                columns: [1, 2, 3],
                                format: {
                                    body: formatColumn
                                }
                            }
                        },
                        {
                            extend: 'excel',
                            exportOptions: {
                                columns: [1, 2, 3],
                                format: {
                                    body: formatColumn
                                }
                            }
                        },
                    ]
                });

                $("#serverStatusTable").find(".dt-buttons a").removeClass();
                $("#serverStatusTable").find(".dt-buttons a").addClass("btn btn-primary ml-2");

                pingForTable();

                $("#serverStatusModal").modal('show');
            },
            function(response) {
                response = JSON.parse(response);
                console.log(response);
                showSwal(response.message, 'error');
            });
    }


    var intervalId = -1;
    function setIntervalForPing() {
        let interval = $("#timeInterval option:selected").val();

        if (interval > 0)
            intervalId= setInterval(() => {
                pingAll();
                pingForTable();
            }, interval);
    }

    setIntervalForPing();
    getSavedIps();



    $('#timeInterval').on('change', () => {
        if(intervalId > 0)
            clearInterval(intervalId);
        setIntervalForPing();
    });




    $("#addIpIp").on('input', function() {
        let $name = $("#addIpName");
        let newValue = $(this).val();
        let name = $name.val();

        if (name == newValue.slice(0, newValue.length - 1) || name.slice(0, name.length - 1) == newValue)
            $name.val(newValue)
    });

    $("#addIpModal").on("hidden.bs.modal", () => {
        $('#ipAddStatus').html('');
    });
</script>
