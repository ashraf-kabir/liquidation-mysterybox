/*basic rreset*/

body{
	width: 100%;
	overflow-x: hidden;
}
/*!
 * Start Bootstrap - Simple Sidebar (https://startbootstrap.com/template-overviews/simple-sidebar)
 * Copyright 2013-2019 Start Bootstrap
 * Licensed under MIT (https://github.com/BlackrockDigital/startbootstrap-simple-sidebar/blob/master/LICENSE)
 */
body {
  overflow-x: hidden;
}

.divider{
  width: 100%;
  height: 30px;
  clear: both;
  float: none;
  display: block;
  border-bottom: 1px solid #ccc;
}

#sidebar-wrapper {
  min-height: 100vh;
  margin-left: -15rem;
  -webkit-transition: margin .25s ease-out;
  -moz-transition: margin .25s ease-out;
  -o-transition: margin .25s ease-out;
  transition: margin .25s ease-out;
  padding: 2%;
}

#sidebar-wrapper .sidebar-heading {
  padding: 0.875rem 1.25rem;
  font-size: 1.2rem;
}

#sidebar-wrapper .list-group {
  width: 15rem;
}

#page-content-wrapper {
  min-width: 100vw;
}

#wrapper.toggled #sidebar-wrapper {
  margin-left: 0;
}

#content{
	padding: 2%;
	width: 100%;
}

@media (min-width: 768px) {
  #sidebar-wrapper {
    margin-left: 0;
  }

  #page-content-wrapper {
    min-width: 0;
    width: 100%;
  }

  #wrapper.toggled #sidebar-wrapper {
    margin-left: -15rem;
  }
}

#drop_file_zone {
    background-color: #EEE; 
    border: #999 5px dashed;
    width: 290px; 
    height: 200px;
    padding: 8px;
    font-size: 18px;
}
#drag_upload_file {
    width:50%;
    margin:0 auto;
}
#drag_upload_file p {
    text-align: center;
}
#drag_upload_file #selectfile {
    display: none;
}