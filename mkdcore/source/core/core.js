/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
var mkd_events = (function () {
  var topics = {};
  var hOP = topics.hasOwnProperty;

  return {
    subscribe: function (topic, listener) {
      // Create the topic's object if not yet created
      if (!hOP.call(topics, topic)) topics[topic] = [];

      // Add the listener to queue
      var index = topics[topic].push(listener) - 1;

      // Provide handle back for removal of topic
      return {
        remove: function () {
          delete topics[topic][index];
        },
      };
    },
    publish: function (topic, info) {
      // If the topic doesn't exist, or there's no listeners in queue, just leave
      if (!hOP.call(topics, topic)) return;

      // Cycle through topics queue, fire!
      topics[topic].forEach(function (item) {
        item(info != undefined ? info : {});
      });
    },
  };
})();

function mkd_is_number(evt, obj) {
  var charCode = evt.which ? evt.which : event.keyCode;
  var value = obj.value;

  var minuscontains = value.indexOf("-") != -1;
  if (minuscontains) {
    if (charCode == 45) {
      return false;
    }
  }
  if (charCode == 45) {
    return true;
  }

  var dotcontains = value.indexOf(".") != -1;
  if (dotcontains) {
    if (charCode == 46) {
      return false;
    }
  }
  if (charCode == 46) {
    return true;
  }
  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
    return false;
  }
  return true;
}

function mkd_export_table(url) {
  if (url.indexOf("?") > -1) {
    url = url + "&format=csv";
  } else {
    url = url + "?format=csv";
  }
  window.location.href = url;
}
$(document).ready(function () {
  $("#sidebarCollapse").on("click", function () {
    $("#sidebar").toggleClass("active");
  });

  //import csv code
  $("#btn-choose-csv").click(function (e) {
    e.preventDefault();
    $("#csv-file").trigger("click");
  });

  $("#csv-file").change(function () {
    $("#import-csv").trigger("submit");
  });

  $("#import-csv").submit(function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    var url = $(this).attr("action");
    $(this).addClass("d-none");
    $.ajax({
      url: url,
      type: "POST",
      data: formData,
      success: function (res) {
        var html = "";
        if (res.preview == true) {
          var data = res.data;
          for (var i = 0; i < data.length; i++) {
            html += "<tr>";
            for (var x = 0; x < data[i].length; x++) {
              html += "<td>" + data[i][x] + "</td>";
            }
            html += "</tr>";
          }
          $("#csv-table-body").html(html);
          $("#csv-table").removeClass("d-none");
          $("#btn-save-csv").removeClass("d-none");
        }
      },
      cache: false,
      contentType: false,
      processData: false,
    });
  });

  $("#btn-save-csv").click(function (e) {
    e.preventDefault();
    var model = $("#btn-csv-upload-dialog").data("model");
    $("#import-csv").attr("action", "/v1/api/file/import/" + model);
    $("#import-csv").trigger("submit");
  });

  $(".modal-image").click(function () {
    var src = $(this).attr("src");
    $("#modal-image-slot").attr("src", src);
    $("#modal-image-show").modal("show");
  });
});
