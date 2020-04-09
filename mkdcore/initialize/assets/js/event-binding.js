
    // binding all json fetch data 
    // order of functions important
    
    function bindBtnRemoveCompany(){
      $('.remove-company').click(function(e){
          e.preventDefault();
          var type = $(this).data('type');
          var id = $(this).data('id').toString();
          var current_items = $('#' + type).val();
          var current_items_array = current_items.split(',');
          console.log(current_items_array);
          var index = current_items_array.indexOf(id);
          if(index !== -1){
            current_items_array.splice(index, 1);
            $('#' + type).val( current_items_array.join());
            $(this).closest("tr").remove();
          }  
      });
    }
 
    function  bindBtnRemoveBroadcastCompany(){
      $('.btn-remove-broadcast-company').click(function(e){
          e.preventDefault();
          var broadcast_companies =  $.makeArray(JSON.parse($('#broadcast').val()));
          var id = $(this).attr('data-id');
         // var index = current_dvd_scenes_array.indexOf(dvd_scene_id);
           for(var i = 0; i < broadcast_companies.length; i++ ){
             if(broadcast_companies[i].company_id == id){
                broadcast_companies.splice(i,1);
                $('#broadcast').val(JSON.stringify(broadcast_companies));
                $(this).closest("tr").remove();
                return; 
             }
           }
      });
    }

    function bindBtnRemoveScene(){
      $('.btn-remove-scene').click(function(e){
         e.preventDefault();
        if (confirm("Do you want to remove scene from dvd")) {
           var action = $('#selected-dvd-scenes').data('action');
           var dvd_cover_scene = $('#dvd_cover_scene').val();
           var dvd_scene_id = $(this).data('id').toString();
           if(dvd_scene_id == dvd_cover_scene ){
             $('#dvd_cover_scene').val("");
           }
           var current_dvd_scenes = $('#dvd_scenes').val();
           if(action === 'edit'){
               $.getJSON( config.base_url + `admin/dvds/remove_dvd_scene?dvd_scene_id=${dvd_scene_id}`, function(data){
                 if(data[0].error === false){
                   //remove id from hidden input
                    var current_dvd_scenes_array = current_dvd_scenes.split(',');
                    var index = current_dvd_scenes_array.indexOf(dvd_scene_id);
                    if(index !== -1){
                      current_dvd_scenes_array.splice(index, 1);
                      $('#dvd_scenes').val( scene_array.join());
                      $(this).closest("tr").remove();
                    }         
                  }
                  else{
                    alert('error removing scene from dvd');
                  }
               });
            }
            else{
               var current_dvd_scenes_array = current_dvd_scenes.split(',');
               console.log( current_dvd_scenes_array);
               var index = current_dvd_scenes_array.indexOf(dvd_scene_id);
               console.log(index);
               if(index !== -1){
                  current_dvd_scenes_array.splice(index, 1);
                  $('#dvd_scenes').val( current_dvd_scenes_array.join());
                  $(this).closest("tr").remove();
                }  
             } 
          } 
       });
     }

    function bindCkbSetSceneAsCover(){
      $('.scene-as-cover').change(function(){
         var scene_id = $(this).data('id');
         $('input:checkbox').prop('checked',false);
         $(this).prop('checked',true);
          $('#dvd_cover_scene').val(scene_id);
      });
    }

    function bindPickSceneButtonClick(){
        $('.pick-scene').click(function(){
           var scene_item = $(this).data('scene');
           var current_scenes = $('#dvd_scenes').val();
           var scene_array = current_scenes.split(',');
            var scene_ids = "";
           if(current_scenes !== ''){
              for(var i = 0 ; i < scene_array.length ; i ++){
                if(scene_item.id == scene_array[i] ){
                  return;
                }
              }
              scene_array.push(scene_item.id);
              scene_ids = scene_array.join();
            }else{
              scene_ids = scene_item.id;
            }
            $('#dvd_scenes').val( scene_ids);
            var html = `
                <tr>
                   <td>${scene_item.name}</td>
                   <td>${scene_item.studio}</td>
                   <td>${scene_item.status}</td>
                   <td>
                      <input class="form-check-input scene-as-cover" type="checkbox" value=""  data-id='${scene_item.id}' >
                      <label class="form-check-label" for="defaultCheck1">
                          Make cover
                      </label>
                   </td>
                   <td><a href='#' class='btn btn-primary btn-remove-scene'  data-id='${scene_item.id}'>remove</a></td>
                </tr>
            `;
            $('#selected-dvd-scenes').append(html);
            bindBtnRemoveScene();
            bindCkbSetSceneAsCover();
        });
    }
