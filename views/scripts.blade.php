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
                finalHtml += ip;
            }

            finalHtml +=
                /*html*/
                `
                <div class="p-3 col-lg-3 col-md-4" style="height: 242.1px">
                    <div class="card card-body d-flex justify-content-center align-items-center align-self-center h-100">
                        <button onclick="$('#addIpModal').modal('show')" class="btn btn-primary">
                            <span class="fas fa-plus" style="font-size:1rem"></span>
                        </button>
                    </div>
                </div>
                `;

            $("#ips").html(finalHtml);


            pingAll();
            $("#serverStatusModal").modal('show');

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
            intervalId = setInterval(() => {
                pingAll();
                pingForTable();
            }, interval);
    }

    setIntervalForPing();
    createServerStatusTable();




    $('#timeInterval').on('change', () => {
        if (intervalId > 0)
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

    $("#serverStatusModal").on("hidden.bs.modal", () => {
        createServerStatusTable();
    });
</script>
