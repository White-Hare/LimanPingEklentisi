 <div id="ipNamePair-{{ $id }}" class="p-3 col-lg-3">
     <div class="card card-body d-flex flex-column ipNamePair">
         <input type="hidden" id="id" value="{{ $id }}" />
         <div id="nameDiv" class="row my-1">
             <div class="col-2 align-self-center align-items-center"><label for="name">Isim</label></div>
             <div class="col-10"><input id="name" class="form-control listValueKeyPairInput" type="text"
                     value="{{ $name }}" /></div>
         </div>
         <div id="ipDiv" class="row my-1">
             <div class="col-2 align-self-center align-items-center"><label for="ip">Ip</label></div>
             <div class="col-10"><input id="ip" class="form-control listValueKeyPairInput" type="text"
                     value="{{ $ip }}" /></div>
         </div>
         <div id="iconDiv" class="row my-1 align-self-center"><span class="fas fa-times text-danger"
                 style="font-size:2em"></span></div>
         <div class="row my-1 align-self-center align-items-center">
             <div class="col">
                 <input type="button" class="btn btn-success" value="{{__("Güncelle")}}"
                     onclick="updateIp('{{ $id }}')">
             </div>
             <div class="col">
                 <input type="button" class="btn btn-danger" value="{{__("Sil")}}" onclick="deleteIp('{{ $id }}')">
             </div>
             <!--<div class="col">
                                        <input type="button" class="btn btn-primary" value="Ping" onclick="ping('{{ $ip }}')">
                                    </div>-->
         </div>
     </div> 
 </div>
