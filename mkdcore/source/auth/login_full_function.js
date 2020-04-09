/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
$(document).ready(function() {
  $("#sidebarCollapse").on("click", function() {
    $("#sidebar").toggleClass("active");
  });

  function toggleResetPswd(e) {
    e.preventDefault();
    $("#mkd-login-container .mkd-login-form-container").toggle(); // display:block or none
    $("#mkd-login-container .mkd-reset-form-container").toggle(); // display:block or none
  }

  function toggleSignUp(e) {
    e.preventDefault();
    $("#mkd-login-container .mkd-login-form-container").toggle(); // display:block or none
    $("#mkd-login-container .mkd-form-signup-container").toggle(); // display:block or none
  }

  $("#mkd-login-container #mkd-forgot-password-link").click(toggleResetPswd);
  $("#mkd-login-container #mkd-cancel-reset-link").click(toggleResetPswd);
  $("#mkd-login-container #mkd-signup-button").click(toggleSignUp);
  $("#mkd-login-container #mkd-cancel-signup-link").click(toggleSignUp);
});
