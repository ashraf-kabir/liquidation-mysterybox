/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
$(document).ready(function() {
  $(".mkd-setting-change").blur(function() {
    var id = $(this).attr("data-id");
    var value = $(this).val();
    $.ajax({
      type: "POST",
      url: "/v1/api/admin/settings/edit/" + id,
      data: {
        value: value
      }
    }).done(function(data) {
      $("#snackbar").css("visibility", "visible");
      setTimeout(function() {
        $("#snackbar").css("visibility", "hidden");
      }, 2000);
    });
  });
  $(".mkd-setting-select-change").change(function() {
    var id = $(this).attr("data-id");
    var value = $(this).val();
    $.ajax({
      type: "POST",
      url: "/v1/api/admin/settings/edit/" + id,
      data: {
        value: value
      }
    }).done(function(data) {
      $("#snackbar").css("visibility", "visible");
      setTimeout(function() {
        $("#snackbar").css("visibility", "hidden");
      }, 2000);
    });
  });
});
