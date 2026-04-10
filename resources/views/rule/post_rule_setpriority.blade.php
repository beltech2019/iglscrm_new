@extends('auth.layouts')

@section('content')

  <style>
    #body {
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .dragcss {
      display: flex;
      flex-direction: row; /* Change to "row" for horizontal layout */
      gap: 10px;
      padding: 10px;
      border: 2px solid #ccc;
      border-radius: 5px;
      width: fit-content;
    }

    .dragbtn {
      cursor: move;
      padding: 10px;
      /* border: 1px solid #ddd; */
      background-color: #e5e5e57d;
      border-radius: 4px;
    }
    .priorityBox {
    border: 1px solid #ddd;
    background-color: #ffff;
    border-radius: 4px;
    padding: 10px 15px 30px;
    margin: 20px 0px;
    width:""
}
  </style>


<form action="/addPostAssignRulePriority" method="POST">
    @csrf
    <div class="container-fluid">
        <div class="div_container">
            <div class="bgwhite2">
                <div class="borderalignheading">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="heading_two">
                                <h2><i class="bi bi-ticket iconsbg2"></i>Set Priority</h2>
                            </div>
                        </div>
                       
                    </div>
                </div>
                <div class="mainrow">
                  <div class="priorityBox">
                    <label class="form-check-label" for="post"  style="margin-left: 3.5em;margin-bottom: 4px;"><i class="bi bi-chat-left-text"></i> Post</label>
                    <div class="col-md-12" id="post">
                    <div class="HLable" style="display: flex;gap: 17px;align-items: center;">
                    <label style="margin:8px 0px;">High</label>
                    <div class="dragcss" id="container" ondrop="drop(event)" ondragover="allowDrop(event)">
                          <div class="draggable dragbtn" draggable="true" ondragstart="drag(event)" id="item1" data-priority="1" >
                            <span>{{$post && isset($post[0]['label'])?$post[0]['label']:''}}</span>
                            <input type="hidden" name="{{$post && isset($post[0]['key'])?$post[0]['key']:''}}" id="item1-value" value="1">
                          </div>
                          <div class="draggable dragbtn" draggable="true" ondragstart="drag(event)" id="item2" data-priority="2">
                            <span>{{$post && isset($post[1]['label'])?$post[1]['label']:''}}</span>
                            <input type="hidden" name="{{$post && isset($post[1]['key'])?$post[1]['key']:''}}" id="item2-value" value="2">
                          </div>
                          <div class="draggable dragbtn" draggable="true" ondragstart="drag(event)" id="item3" data-priority="3">
                            <span>{{$post && isset($post[2]['label'])?$post[2]['label']:''}}</span>
                            <input type="hidden" name="{{$post && isset($post[2]['key'])?$post[2]['key']:''}}" id="item3-value" value="3">
                          </div>
                          <div class="draggable dragbtn" draggable="true" ondragstart="drag(event)" id="item4" data-priority="4">
                            <span>{{$post && isset($post[3]['label'])?$post[3]['label']:''}}</span>
                            <input type="hidden" name="{{$post && isset($post[3]['key'])?$post[3]['key']:''}}" id="item4-value" value="4">
                          </div>
                          <div class="draggable dragbtn" draggable="true" ondragstart="drag(event)" id="item5" data-priority="5">
                            <span>{{$post && isset($post[4]['label'])?$post[4]['label']:''}}</span>
                            <input type="hidden" name="{{$post && isset($post[4]['key'])?$post[4]['key']:''}}" id="item5-value" value="5">
                          </div>
                          <div class="draggable dragbtn" draggable="true" ondragstart="drag(event)" id="item6" data-priority="6">
                            <span>{{$post && isset($post[5]['label'])?$post[5]['label']:''}}</span>
                            <input type="hidden" name="{{$post && isset($post[5]['key'])?$post[5]['key']:''}}" id="item6-value" value="6">  
                          </div>
                        </div>
                        <label style="margin:8px 0px; float: right;">Low</label>
                    </div>
                   
                    </div>
                    </div>
                    <div class="priorityBox">
                    <label class="form-check-label" for="ticket" style="margin-left: 3.5em;margin-bottom: 4px;"><i class="bi bi-ticket"></i>
                        Ticket</label>
                      <div class="col-md-12" id="ticket">
                      <div class="HLable" style="display: flex;gap: 17px;align-items: center;">
                    <label style="margin:8px 0px;">High</label>
                        <div class="dragcss" id="container1" ondrop="drop1(event)" ondragover="allowDrop1(event)">
                            <div class="draggable1 dragbtn" draggable="true" ondragstart="drag(event)" id="item7" data-priority="7" >
                              <span>{{$ticket && isset($ticket[0]['label'])?$ticket[0]['label']:''}}</span>
                              <input type="hidden" name="{{$ticket && isset($ticket[0]['key'])?$ticket[0]['key']:''}}" id="item1-value" value="1">
                            </div>
                            <div class="draggable1 dragbtn" draggable="true" ondragstart="drag(event)" id="item8" data-priority="8">
                              <span>{{$ticket && isset($ticket[1]['label'])?$ticket[1]['label']:''}}</span>
                              <input type="hidden" name="{{$ticket && isset($ticket[1]['key'])?$ticket[1]['key']:''}}" id="item2-value" value="2">
                            </div>
                            <div class="draggable1 dragbtn" draggable="true" ondragstart="drag(event)" id="item9" data-priority="9">
                              <span>{{$ticket && isset($ticket[2]['label'])?$ticket[2]['label']:''}}</span>
                              <input type="hidden" name="{{$ticket && isset($ticket[2]['key'])?$ticket[2]['key']:''}}" id="item3-value" value="3">
                            </div>
                            <div class="draggable1 dragbtn" draggable="true" ondragstart="drag(event)" id="item10" data-priority="10">
                              <span>{{$ticket && isset($ticket[3]['label'])?$ticket[3]['label']:''}}</span>
                              <input type="hidden" name="{{$ticket && isset($ticket[3]['key'])?$ticket[3]['key']:''}}" id="item4-value" value="4">
                            </div>
                            <div class="draggable1 dragbtn" draggable="true" ondragstart="drag(event)" id="item11" data-priority="11">
                              <span>{{$ticket && isset($ticket[4]['label'])?$ticket[4]['label']:''}}</span>
                              <input type="hidden" name="{{$ticket && isset($ticket[4]['key'])?$ticket[4]['key']:''}}" id="item5-value" value="5">
                            </div>
                            <div class="draggable1 dragbtn" draggable="true" ondragstart="drag(event)" id="item12" data-priority="12">
                              <span>{{$ticket && isset($ticket[5]['label'])?$ticket[5]['label']:''}}</span>
                              <input type="hidden" name="{{$ticket && isset($ticket[5]['key'])?$ticket[5]['key']:''}}" id="item6-value" value="6">  
                            </div>
                          </div>
                    <label style="margin:8px 0px; float: right;">Low</label>
                          </div>
                        </div>
                        </div>
                        <div class="priorityBox">
                    <label class="form-check-label" for="lead" style="margin-left: 3.5em;margin-bottom: 4px;"><i class="bi bi-funnel"></i>
                        Lead</label>
                    <div class="col-md-12" id="lead">
                    <div class="HLable" style="display: flex;gap: 17px;align-items: center;">
                    <label style="margin:8px 0px;">High</label>
                    <div class="dragcss" id="container2" ondrop="drop2(event)" ondragover="allowDrop2(event)">
                          <div class="draggable2 dragbtn" draggable="true" ondragstart="drag(event)" id="item13" data-priority="13" >
                            <span>{{$lead && isset($lead[0]['label'])?$lead[0]['label']:''}}</span>
                            <input type="hidden" name="{{$lead && isset($lead[0]['key'])?$lead[0]['key']:''}}" id="item1-value" value="1">
                          </div>
                          <div class="draggable2 dragbtn" draggable="true" ondragstart="drag(event)" id="item14" data-priority="14">
                            <span>{{$lead && isset($lead[1]['label'])?$lead[1]['label']:''}}</span>
                            <input type="hidden" name="{{$lead && isset($lead[1]['key'])?$lead[1]['key']:''}}" id="item2-value" value="2">
                          </div>
                          <div class="draggable2 dragbtn" draggable="true" ondragstart="drag(event)" id="item15" data-priority="15">
                            <span>{{$lead && isset($lead[2]['label'])?$lead[2]['label']:''}}</span>
                            <input type="hidden" name="{{$lead && isset($lead[2]['key'])?$lead[2]['key']:''}}" id="item3-value" value="3">
                          </div>
                          <div class="draggable2 dragbtn" draggable="true" ondragstart="drag(event)" id="item16" data-priority="16">
                            <span>{{$lead && isset($lead[3]['label'])?$lead[3]['label']:''}}</span>
                            <input type="hidden" name="{{$lead && isset($lead[3]['key'])?$lead[3]['key']:''}}" id="item4-value" value="4">
                          </div>
                          <div class="draggable2 dragbtn" draggable="true" ondragstart="drag(event)" id="item17" data-priority="17">
                            <span>{{$lead && isset($lead[4]['label'])?$lead[4]['label']:''}}</span>
                            <input type="hidden" name="{{$lead && isset($lead[4]['key'])?$lead[4]['key']:''}}" id="item5-value" value="5">
                          </div>
                          <div class="draggable2 dragbtn" draggable="true" ondragstart="drag(event)" id="item18" data-priority="18">
                            <span>{{$lead && isset($lead[5]['label'])?$lead[5]['label']:''}}</span>
                            <input type="hidden" name="{{$lead && isset($lead[5]['key'])?$lead[5]['key']:''}}" id="item6-value" value="6">  
                          </div>
                        </div>
                    <label style="margin:8px 0px; float: right;">Low</label>
                    </div></div></div>
                    <div class="col-md-12">
                        <div class="buttons_prime">
                            <button type="submit" class="btn btn-danger {{ addUIComponent('ADMINMANAGEMENT_POST_RULE_SUBMIT') }}">Submit</button>
                            <a type="button" href="/postAssignRuleList" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>


<script>
$("#admin").addClass("active");

function openNav() {
    document.getElementById("mySidenav").style.width = "250px";
    document.getElementById("main").style.marginLeft = "250px";
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "64px";
    document.getElementById("main").style.marginLeft = "64px";
}
  function allowDrop(event) {
    event.preventDefault();
  }

  function drag(event) {
    event.dataTransfer.setData("text", event.target.id);
  }

  function drop(event) {
    event.preventDefault();
    var data = event.dataTransfer.getData("text");
    var draggedElement = document.getElementById(data);
    var container = event.target.closest('#container');

    if (container.contains(draggedElement)) {
      var draggedPriority = draggedElement.getAttribute("data-priority");
      var draggedValue = draggedElement.getAttribute("data-value");
      
      var targetElement = event.target.closest('.draggable');

      if (targetElement) {
        var targetPriority = targetElement.getAttribute("data-priority");
        var targetValue = targetElement.getAttribute("data-value");

        // Swap the priorities
        draggedElement.setAttribute("data-priority", targetPriority);
        targetElement.setAttribute("data-priority", draggedPriority);

        // Swap the values
        draggedElement.setAttribute("data-value", targetValue);
        targetElement.setAttribute("data-value", draggedValue);

        // Swap the elements in the DOM
        var containerChildren = container.children;
        var draggedIndex = Array.from(containerChildren).indexOf(draggedElement);
        var targetIndex = Array.from(containerChildren).indexOf(targetElement);

        container.insertBefore(draggedElement, containerChildren[targetIndex]);
        container.insertBefore(targetElement, containerChildren[draggedIndex]);

        // Update the hidden input values
        updateHiddenInputValues();
      }
    }
  }

  function updateHiddenInputValues() {
    var elements = container.querySelectorAll('.draggable');
    var count=1;
    elements.forEach(function (element, index) {
      var childinput=element.querySelector("input");
      childinput.value = count;
      count++;
    });
  }

  
  function allowDrop1(event) {
    event.preventDefault();
  }

  function drag1(event) {
    event.dataTransfer.setData("text", event.target.id);
  }

  function drop1(event) {
    event.preventDefault();
    var data = event.dataTransfer.getData("text");
    var draggedElement = document.getElementById(data);
    var container1 = event.target.closest('#container1');

    if (container1.contains(draggedElement)) {
      var draggedPriority = draggedElement.getAttribute("data-priority");
      var draggedValue = draggedElement.getAttribute("data-value");
      
      var targetElement = event.target.closest('.draggable1');

      if (targetElement) {
        var targetPriority = targetElement.getAttribute("data-priority");
        var targetValue = targetElement.getAttribute("data-value");

        // Swap the priorities
        draggedElement.setAttribute("data-priority", targetPriority);
        targetElement.setAttribute("data-priority", draggedPriority);

        // Swap the values
        draggedElement.setAttribute("data-value", targetValue);
        targetElement.setAttribute("data-value", draggedValue);

        // Swap the elements in the DOM
        var containerChildren = container1.children;
        var draggedIndex = Array.from(containerChildren).indexOf(draggedElement);
        var targetIndex = Array.from(containerChildren).indexOf(targetElement);

        container1.insertBefore(draggedElement, containerChildren[targetIndex]);
        container1.insertBefore(targetElement, containerChildren[draggedIndex]);

        // Update the hidden input values
        updateHiddenInputValues1();
      }
    }
  }

  function updateHiddenInputValues1() {
    var elements = container1.querySelectorAll('.draggable1');
    var count=1;
    elements.forEach(function (element, index) {
      var childinput=element.querySelector("input");
      childinput.value = count;
      count++;
    });
  }

  
  function allowDrop2(event) {
    event.preventDefault();
  }

  function drag2(event) {
    event.dataTransfer.setData("text", event.target.id);
  }

  function drop2(event) {
    event.preventDefault();
    var data = event.dataTransfer.getData("text");
    var draggedElement = document.getElementById(data);
    var container2 = event.target.closest('#container2');

    if (container2.contains(draggedElement)) {
      var draggedPriority = draggedElement.getAttribute("data-priority");
      var draggedValue = draggedElement.getAttribute("data-value");
      
      var targetElement = event.target.closest('.draggable2');

      if (targetElement) {
        var targetPriority = targetElement.getAttribute("data-priority");
        var targetValue = targetElement.getAttribute("data-value");

        // Swap the priorities
        draggedElement.setAttribute("data-priority", targetPriority);
        targetElement.setAttribute("data-priority", draggedPriority);

        // Swap the values
        draggedElement.setAttribute("data-value", targetValue);
        targetElement.setAttribute("data-value", draggedValue);

        // Swap the elements in the DOM
        var containerChildren = container2.children;
        var draggedIndex = Array.from(containerChildren).indexOf(draggedElement);
        var targetIndex = Array.from(containerChildren).indexOf(targetElement);

        container2.insertBefore(draggedElement, containerChildren[targetIndex]);
        container2.insertBefore(targetElement, containerChildren[draggedIndex]);

        // Update the hidden input values
        updateHiddenInputValues2();
      }
    }
  }

  function updateHiddenInputValues2() {
    var elements = container2.querySelectorAll('.draggable2');
    var count=1;
    elements.forEach(function (element, index) {
      var childinput=element.querySelector("input");
      childinput.value = count;
      count++;
    });
  }
</script>



@endsection