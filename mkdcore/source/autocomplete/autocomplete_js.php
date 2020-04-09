if(document.getElementById('{{{search_field}}}_autocomplete')){
    var options = {   
       url: function(phrase){
       return ' {{{url}}}' + phrase;  
    },
    getValue: function(element) { 
        return element.{{{field_label_field}}};
    },
    list: {
        match: {
        enabled: true
    },
    onClickEvent: function(){  
        var name = $('#{{{search_field}}}_autocomplete').getSelectedItemData().{{{display_field}}};
        var id = $('#{{{search_field}}}_autocomplete').getSelectedItemData().{{{value_field}}};
        $('#{{{search_field}}}_autocomplete').val(name);
        $('#{{{search_field}}}_autocomplete_value_field').val(id);    
      }
    },
        theme: "square"
    };
    $('#{{{search_field}}}_autocomplete').easyAutocomplete(options);
}