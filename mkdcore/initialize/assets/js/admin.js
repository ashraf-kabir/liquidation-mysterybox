$(document).ready(function() {

    $("select[multiple='multiple']").bsMultiSelect({
         selectedPanelDefMinHeight: 'calc(2.25rem + 2px)',  // default size
         selectedPanelLgMinHeight: 'calc(2.875rem + 2px)',  // LG size
         selectedPanelSmMinHeight: 'calc(1.8125rem + 2px)', // SM size
         selectedPanelDisabledBackgroundColor: '#e9ecef',   // disabled background
         selectedPanelFocusBorderColor: '#80bdff',          // focus border
         selectedPanelFocusBoxShadow: '0 0 0 0.2rem rgba(0, 123, 255, 0.25)',  // foxus shadow
         selectedPanelFocusValidBoxShadow: '0 0 0 0.2rem rgba(40, 167, 69, 0.25)',  // valid foxus shadow
         selectedPanelFocusInvalidBoxShadow: '0 0 0 0.2rem rgba(220, 53, 69, 0.25)',  // invalid foxus shadow
         inputColor: '#495057', // color of keyboard entered text
         selectedItemContentDisabledOpacity: '.65' // btn disabled opacity used
      });
            
    
    function statusText(num){
      return num === 0 ? 'inactive' : 'active';
    }

    function populate_broadcast_table(companies_array){
       var html = "";
       for(var i = 0; i < companies_array.length; i++ ){
            html += `
                <tr>
                    <td>${companies_array[i].name}</td>
                    <td>${companies_array[i].right_id}</td>
                    <td>${companies_array[i].terms}</td>
                    <td><a href='#' data-id=${companies_array[i].company_id} class='btn-remove-broadcast-company btn btn-primary btn-link btn-sm'>remove</a></td>
                </tr>
            `;
        } 
        $('#broadcast_table').html(html); 
        bindBtnRemoveBroadcastCompany();     
    }

  

    function getThumbnail(img){
      if(img === ''){
        return  config.base_url + 'assets/img/default.jpg';
      }
      return img;
    } 

    function selectScene(event, data){
      event.preventDefault();
      console.log(data);
    }

    var right_type = '';
    var search_type ='';
    
    if($('#producers-object').length){
       var producers =  JSON.parse($('#producers-object').val());
    }
    if($('#actors-object').length){
        var actors =  JSON.parse($('#actors-object').val());
    }


	/*collapasing nav sidebar*/
    $("#sidebarCollapse").click(function(e) {
      	e.preventDefault();
      	$("#wrapper").toggleClass("toggled");
    });

    //search companies
    $('#searchCompanies').on('show.bs.modal', function(e) {
    	right_type = $(e.relatedTarget).data('id');
    });

      //search broadcast companies
    $('.btn-select-company').click(function(e){
    	e.preventDefault();
    	var name = $(this).attr('data-companyName');
    	var id = $(this).attr('data-companyId');
      var action = $('#' + right_type+ '_table').attr('data-action');
      var current_companies = $('#' + right_type).val();
        if(current_companies !== ''){
          var companies_array = current_companies.split(',');
          for(var i = 0 ; i < companies_array.length ; i ++){
            if(id ==  companies_array[i] ){
              return;
            }
          }
          companies_array.push(id);
          var company_ids = companies_array.join();
          $('#'+ right_type).val(company_ids);
            $('#'+ right_type +'_table').append("<tr><td>"+  name +"</td><td><a href'#' data-id='"+ id +"' data-type='"+  right_type +"' class='remove-company btn btn-sm btn-link btn-primary'>remove</a></td></tr>");
          bindBtnRemoveCompany();
          return;
        }
        $('#'+right_type).val(id);
          $('#'+ right_type +'_table').append("<tr><td>"+  name +"</td><td><a href'#' data-id='"+ id +"'  data-type='"+  right_type +"' class='remove-company btn btn-sm btn-link btn-primary'>remove</a></td></tr>");
        bindBtnRemoveCompany();
        return;
    });
  
     $('.btn-select-network-company').click(function(e){
        e.preventDefault();
        var action = $('#networks_table').data('action');
        var  right_type = 'networks';
        var id = $(this).attr('data-companyId');
        var name = $(this).attr('data-companyName');
         var current_companies = $('#networks').val();
           if(current_companies !== ''){
              var companies_array = current_companies.split(',');
              for(var i = 0 ; i < companies_array.length ; i ++){
                if(id ==  companies_array[i] ){
                    return;
                }
              }
              companies_array.push(id);
              var company_ids = companies_array.join();
              $('#'+ right_type).val(company_ids); 
               $('#'+ right_type +'_table').append("<tr><td>"+  name +"</td><td><a href'#' data-type='"+  right_type +"' data-id='"+ id +"' class='remove-company btn btn-sm btn-link btn-primary'>remove</a></td></tr>");
              bindBtnRemoveCompany();
              return;
           }
           else{
             $('#'+right_type).val(id);
               $('#'+ right_type +'_table').append("<tr><td>"+  name +"</td><td><a href'#' data-type='"+  right_type +"' data-id='"+ id +"' class='remove-company btn btn-sm btn-link btn-primary'>remove</a></td></tr>");
             bindBtnRemoveCompany();
             return;
           } 
     });

     $('.btn-select-view-share-company').click(function(e){
       e.preventDefault();
        var  right_type = 'view_share';
        var action = $('#'+ right_type +'_table').data('action');
        var id = $(this).attr('data-companyId');
        var name = $(this).attr('data-companyName');
         var current_companies = $('#' + right_type).val();
           if(current_companies !== ''){
              var companies_array = current_companies.split(',');
              for(var i = 0 ; i < companies_array.length ; i ++){
                if(id ==  companies_array[i] ){
                    return;
                }
              }
              companies_array.push(id);
              var company_ids = companies_array.join();
              $('#'+ right_type).val(company_ids); 
              $('#'+ right_type +'_table').append("<tr><td>"+  name +"</td><td><a href'#' data-id='"+ id +"' data-type='"+  right_type +"' class='remove-company btn btn-sm btn-link btn-primary'>remove</a></td></tr>");
              bindBtnRemoveCompany();
              return;
           }
           else{
             $('#'+right_type).val(id);
             $('#'+ right_type +'_table').append("<tr><td>"+  name +"</td><td><a href'#' data-id='"+ id +"'  data-type='"+  right_type +"' class='remove-company btn btn-sm btn-link btn-primary'>remove</a></td></tr>");
             bindBtnRemoveCompany();
             return;
           } 
     });
    
    $('.btn-select-broadcast-company').click(function(e){
        e.preventDefault();
        console.log('click');
        var name = $(this).attr('data-companyName');
        var id = $(this).attr('data-companyId');
        var right = $('#broadcast_rights' + id).val();
        var term = $('#terms' + id).val();
        var company_obj = {
            company_id : id,
            name :  name, 
            right_id : right,
            terms : term  
        };
        populate_broadcast_table([]);
        var current_companies = $('#broadcast').val();
        var companies_array = [];
        if(current_companies !== ""){
            companies_array =  $.makeArray(JSON.parse(current_companies));
            for(var i = 0; i <  companies_array.length; i++ ){
                if( companies_array[i].company_id === id ){
                    companies_array[i] = company_obj;
                    $('#broadcast').val(JSON.stringify( companies_array));
                    populate_broadcast_table(companies_array);
                    return; 
                }
            }
            companies_array.push(company_obj);
            $('#broadcast').val(JSON.stringify( companies_array));
            populate_broadcast_table(companies_array);
            return;
        }
        companies_array.push(company_obj);
        $('#broadcast').val(JSON.stringify( companies_array));  
        populate_broadcast_table(companies_array);
        return;   
    });

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

/******************************************************************************************/
/***************************ADD DVD PAGE***********************************************/
     
     $('.btn-remove-scene').click(function(e){ // has a bindeded version
         e.preventDefault();
        if (confirm("Do you want to remove scene from dvd")) {
           $(this).closest("tr").remove();
        } 
     });    

     $('.scene-as-cover').change(function(){
         var status = $(this).is(':checked');
         if(status == true){
            var scene_id = $(this).data('id');
            $('input:checkbox').prop('checked',false);
            $(this).prop('checked',true);
            $('#dvd_cover_scene').val(scene_id);
         }
      });

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

    $('.filter-scene-by-category').click(function(e){
        e.preventDefault();
        var cat_id = $(this).data('id');
        var filter_months = $('#scene_filer').val();
        var html_table_rows = "";
         $('#filter-scenes').html("");
         $.getJSON( config.base_url + `admin/dvds/filter_dvd_scenes?category_id=${cat_id}&filter_after=${filter_months}`, function(data){
           var scene_obj = null;
           for(var i = 0 ; i < data.length; i ++){
               var scene_status = data[i].status === 0 ? 'inactive' : 'active';
               scene_obj = { name : data[i].title, studio : data[i].name, id : data[i].id, status: scene_status };
               html_table_rows += `
                 <tr>
                   <td>${data[i].title}</td>
                   <td>${data[i].name}</td>
                   <td>${statusText(data[i].status)}</td>
                   <td><a class='btn btn-primary pick-scene' data-scene='${ JSON.stringify(scene_obj)}' >Select</a></td>
                 </tr>
                `;
               $('#filter-scenes').append(html_table_rows);
               html_table_rows = '';
           }
            bindPickSceneButtonClick();
         });
    });

    $('.btn-remove-scene').click(function(e){
      e.preventDefault();
      if (confirm("Do you want to remove scene from dvd")) {
           var action = $('#selected-dvd-scenes').data('action');
           var dvd_scene = $(this).data('scene-id').toString();
           var current_dvd_cover_scene = $('#dvd_cover_scene').val().toString();
           var dvd_scene_id = $(this).data('id').toString();
           if(current_dvd_cover_scene === dvd_scene ){
             $('#dvd_cover_scene').val("");
           }
           var current_dvd_scenes = $('#dvd_scenes').val();
           if(action == 'edit'){
               $.getJSON( config.base_url + `admin/dvds/remove_dvd_scene?dvd_scene_id=${dvd_scene_id}`, function(data){
                 console.log(data);
                 if(data.error === false){
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
               var index = current_dvd_scenes_array.indexOf(dvd_scene_id);
               if(index !== -1){
                  current_dvd_scenes_array.splice(index, 1);
                  $('#dvd_scenes').val( scene_array.join());
                  $(this).closest("tr").remove();
                  alert('scene removed from dvd');
                }  
             } 
          } 
       });
       ////////////////////////////////DVD VIEW PAGE CODE///////////////////////////////
       $('.scene-edited').change(function(){
          var scene_id = $(this).data('id');
          var updated_scenes = $('#updated_scenes').val();
          var updated_scenes_array = [];
          if(updated_scenes !== ''){
            updated_scenes_array = $.makeArray(JSON.parse(updated_scenes));
            for(var i = 0; i < updated_scenes_array.length; i ++ ){
              if(updated_scenes_array[i].scene_id == scene_id){
                  var scene_obj = {
                    scene_id : scene_id,
                    comp_after_date : $('#scene-comp-date'+  scene_id ).val(),
                    next_studio : $('#scene-studio'+  scene_id ).val()
                  };
                  updated_scenes_array[i] = scene_obj;
                  $('#updated_scenes').val(JSON.stringify(updated_scenes_array));
                  return;
              }
            }
            var scene_obj = {
              scene_id : scene_id,
              comp_after_date : $('#scene-comp-date'+  scene_id ).val(),
              next_studio : $('#scene-studio'+  scene_id ).val()
             };
             updated_scenes_array.push(scene_obj);
             $('#updated_scenes').val(JSON.stringify(updated_scenes_array));
             return;
          }
          else{
            var scene_obj = {
              scene_id : scene_id,
              comp_after_date : $('#scene-comp-date'+  scene_id ).val(),
              comp_after_studio : $('#scene-studio'+  scene_id ).val()
            };
            updated_scenes_array.push(scene_obj);
            $('#updated_scenes').val(JSON.stringify(updated_scenes_array));
            return;
          }
         
       });
});

