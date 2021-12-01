@component('modal-component', [
    'id' => 'addIpModal',
    'title' => __('IP Ekle'),
    "notSized" => true,
    'footer' => [
        'text' => __('IP Ekle'),
        'class' => 'btn-primary',
        'onclick' => "addIp()",
    ],
    ])

    <input type="text" id="addIpName" class="form-control" placeholder="Liman120">
    <small>{{ __("Eklemek istediğiniz IP adresine bir ad yazınız. Örnek format: Liman120") }}</small>

    <div class="mb-3"></div>

    <input type="text" id="addIpIp" class="form-control" placeholder="10.0.0.100">
    <small>{{ __("Eklemek istediğiniz IP adresini giriniz. Örnek format: 192.168.20.40") }}</small>
    
    <div class="mb-3"></div>

    <div id="ipAddStatus"></div>
@endcomponent


@component('modal-component', [
    'id' => 'serverStatusModal',
    'title' => __('Server Durumları'),
    'footer' => [
        'text' => __('Kapat'),
        'class' => 'btn-primary',
        'onclick' => "$('#serverStatusModal').modal('hide')",
    ],
    ])

<div id="serverStatusTable">
    
</div>


@endcomponent
