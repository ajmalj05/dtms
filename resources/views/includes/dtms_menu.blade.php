{{-- <div class="row"> --}}
    {{-- <div class="col-xl-12 col-lg-12 col-sm-12"> --}}
        {{-- <div class="card card-sm"> --}}
                
           
            {{-- <p class="btn btn-primary dtmscollapsemenu"  >
                        
                <a data-toggle="collapse" href="#collapseMenus" role="button" aria-expanded="false" aria-controls="collapseMenus">
                    DTMS Menus
                </a>
            </p>


            <div class="text-center p-3 collapse"  id="collapseMenus">
                <ul class="list-group">

                    <a href="{{url('dtmshome/'.$pid)}}" ><li class="list-group-item  {{$isactive=='DH' ? 'active' : ''}}" >DTMS Home</li></a>
                    <a href="{{url('medicalHistory/'.$pid)}}" ><li class="list-group-item" >Medical History</li></a>
                    <a href="{{url('pep')}}" class=""><li class="list-group-item  {{$isactive=='PEP' ? 'active' : ''}}" >Patient education module (PEP)</li></a>
                    <a href="{{url('prescription')}}" class=""><li class="list-group-item  {{$isactive=='PM' ? 'active' : ''}}">Prescription Management</li></a>
                    <a href="{{url('vaccination')}}" class=""><li class="list-group-item  {{$isactive=='VD' ? 'active' : ''}}">Vaccination Details</li></a>
                    <a href="{{url('medications')}}" class=""><li class="list-group-item  {{$isactive=='ME' ? 'active' : ''}}">Medications</li></a>
                    <a href="{{url('miscellaneous')}}" class=""><li class="list-group-item  {{$isactive=='MM' ? 'active' : ''}}">Miscellaneous module</li></a>
                  </ul>

            </div> --}}
        {{-- </div> --}}
    {{-- </div> --}}
{{-- </div> --}}



{{-- <div class="container-fluid h-100">
    <div class="row h-100">
      <div class="col-5 col-md-3 collapse m-0 p-0 h-100 bg-dark" id="collapseMenu">
        <ul class="nav flex-column navbar-dark sticky-top">
          <li class="nav-item">
            <a class="nav-link active" href="#">Active</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Link</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Link</a>
          </li>
          <li class="nav-item">
            <a class="nav-link disabled" href="#">Disabled</a>
          </li>
        </ul>
      </div>
      <div class="col">
        <div class="row">
          <div class="col-12">
            <button class="btn sticky-top colapsemenu" data-toggle="collapse" href="#collapseMenu" role="button">
              <i class="fa fa-gear fa-stack-2x gear"></i>
            </button>   
          </div>
        </div>
      </div>
    </div>
</div> --}}



<!-- Floating Action Button like Google Material -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<div class="floating-container">
  <div class="floating-button"><i class="fa fa-gear"></i></div>
  <div class="element-container">

   {{-- <a href="google.com"> <span class="float-element tooltip-left">
      <i class="material-icons">phone
      </i></a>
    </span>
      <span class="float-element">
      <i class="material-icons">email
</i>
    </span>
      <span class="float-element">
      <i class="material-icons">chat</i>
    </span> --}}
    <div class="text-center p-3  float-element">
      <ul class="list-group">

          <a href="{{url('dtmshome/'.$pid)}}" ><li class="list-group-item   {{$isactive=='DH' ? 'active' : ''}}" >DTMS Home</li></a>
          <a href="{{url('medicalhistory')}}" ><li class="list-group-item" >Medical History</li></a>
          <a href="{{url('pep')}}" class=""><li class="list-group-item  {{$isactive=='PEP' ? 'active' : ''}}" >Patient education module (PEP)</li></a>
          {{-- <a href="{{url('prescription')}}" class=""><li class="list-group-item  {{$isactive=='PM' ? 'active' : ''}}">Prescription Management</li></a> --}}
          <a href="{{url('vaccination')}}" class=""><li class="list-group-item  {{$isactive=='VD' ? 'active' : ''}}">Vaccination Details</li></a>
          {{-- <a href="{{url('medications')}}" class=""><li class="list-group-item  {{$isactive=='ME' ? 'active' : ''}}">Medications</li></a> --}}
          <a href="{{url('miscellaneous')}}" class=""><li class="list-group-item  {{$isactive=='MM' ? 'active' : ''}}">Miscellaneous module</li></a>
        
          <a href="{{url('miscellaneous')}}" class=""><li class="list-group-item  {{$isactive=='MM' ? 'active' : ''}}">Photo</li></a>
          <a href="{{url('miscellaneous')}}" class=""><li class="list-group-item  {{$isactive=='MM' ? 'active' : ''}}">Chart</li></a>
          {{-- <a href="{{url('miscellaneous')}}" class=""><li class="list-group-item  {{$isactive=='MM' ? 'active' : ''}}">Pharmacy</li></a> --}}
          <a href="{{url('miscellaneous')}}" class=""><li class="list-group-item  {{$isactive=='MM' ? 'active' : ''}}">More Details</li></a>
          <a href="{{url('miscellaneous')}}" class=""><li class="list-group-item  {{$isactive=='MM' ? 'active' : ''}}">Abroad Details</li></a>

        </ul>

  </div>
  </div>
</div>


<style>
    .colapsemenu{

        float: right;
        top: 50%;
        /* bottom: 0px; */
        position: fixed;
        z-index: 999;
        right: 0px;
    }
    .gear{
        background: #00a179;
        color: #fff;
        padding: 10px;
    }
    @import url("https://fonts.googleapis.com/css?family=Roboto");
@-webkit-keyframes come-in {
  0% {
    -webkit-transform: translatey(100px);
            transform: translatey(100px);
    opacity: 0;
  }
  30% {
    -webkit-transform: translateX(-50px) scale(0.4);
            transform: translateX(-50px) scale(0.4);
  }
  70% {
    -webkit-transform: translateX(0px) scale(1.2);
            transform: translateX(0px) scale(1.2);
  }
  100% {
    -webkit-transform: translatey(0px) scale(1);
            transform: translatey(0px) scale(1);
    opacity: 1;
  }
}
@keyframes come-in {
  0% {
    -webkit-transform: translatey(100px);
            transform: translatey(100px);
    opacity: 0;
  }
  30% {
    -webkit-transform: translateX(-50px) scale(0.4);
            transform: translateX(-50px) scale(0.4);
  }
  70% {
    -webkit-transform: translateX(0px) scale(1.2);
            transform: translateX(0px) scale(1.2);
  }
  100% {
    -webkit-transform: translatey(0px) scale(1);
            transform: translatey(0px) scale(1);
    opacity: 1;
  }
}
* {
  margin: 0;
  padding: 0;
}

html, body {
  background: #eaedf2;
  font-family: 'Roboto', sans-serif;
}

.floating-container {
  position: fixed;
  right: 0;
  margin: 35px 25px;
  width: 25px;
  height: 0px;
  /* top: 35%; */
  top: 12%;


}

/* .floating-container:hover .floating-button {
  box-shadow: 0 10px 25px rgba(44, 179, 240, 0.6);
  
}
.floating-container:hover .element-container .float-element:nth-child(1) {
  -webkit-animation: come-in 0.4s forwards 0.2s;
          animation: come-in 0.4s forwards 0.2s;
}
.floating-container:hover .element-container .float-element:nth-child(2) {
  -webkit-animation: come-in 0.4s forwards 0.4s;
          animation: come-in 0.4s forwards 0.4s;
}
.floating-container:hover .element-container .float-element:nth-child(3) {
  -webkit-animation: come-in 0.4s forwards 0.6s;
          animation: come-in 0.4s forwards 0.6s;
} */
.floating-container .floating-button {
  position: absolute;
  width: 50px;
  height: 50px;
  background: #2cb3f0;
  bottom: 0;
  border-radius: 50%;
  left: 0;
  right: 0;
  margin: auto;
  color: white;
  line-height: 50px;
  text-align: center;
  font-size: 23px;
  z-index: 100;
  box-shadow: 0 10px 25px -5px rgba(44, 179, 240, 0.6);
  cursor: pointer;
  -webkit-transition: all 0.3s;
  transition: all 0.3s;
}
.floating-container .float-element {
  position: relative;
  display: block;
  border-radius: 50%;
  width: 270px;
  height: 50px;
  margin: 15px auto;
  color: white;
  font-weight: 500;
  text-align: center;
  /* line-height: 50px; */
  z-index: 0;
  /* opacity: 0; */
  display: none;
  -webkit-transform: translateY(100px);
          transform: translateY(100px);
}
.floating-container .float-element .material-icons {
  vertical-align: middle;
  font-size: 16px;
}
/* .floating-container .float-element:nth-child(1) {
  background: #42A5F5;
  box-shadow: 0 20px 20px -10px rgba(66, 165, 245, 0.5);
} */
/* .floating-container .float-element:nth-child(2) {
  background: #4CAF50;
  box-shadow: 0 20px 20px -10px rgba(76, 175, 80, 0.5);
}
.floating-container .float-element:nth-child(3) {
  background: #FF9800;
  box-shadow: 0 20px 20px -10px rgba(255, 152, 0, 0.5);
} */



.list-group{
  right: 225px;
  position: inherit;
  background: #fff;
  z-index: 999;
    top:-130px!important;

}
    </style>

