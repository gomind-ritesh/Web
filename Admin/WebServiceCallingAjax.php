<html>
<head>
<title>Call WebService via AJAX with the accept method required being JSON </title>
<link
      href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp"
      rel="stylesheet"
    />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>

  $(document).ready(function(){
	$("button#getReservations").click(function(){
		var name = $("input#lkup_txt_name").val();
		//Let us call the web service
			
		var url = "http://localhost/Web/Admin/WebService/food/";
		if(name!= "" )
		{
			url= url + "?name=" + name;
		}
		//alert(url);
		$.ajax({
			url: url,
			accepts: "application/json",
			headers:{Accept:"application/json"},
			//headers:{Content-Type: "application/x-www-form-urlencoded"},
			method: "GET", 
			error: function(xhr){
      			if(xhr.status == 404)
      			{
      				$("div#showreservations").html("No reservations were found");
      			}//end if
      			else
      			{
      				alert("An error occured: " + xhr.status + " " + xhr.statusText);
      			}
    		}
    	})
    	.done(function(data)
    	{	
			var table_str = "<br><table><tr><th>Reservation ID</th><th>Reservation Name</th><th>Phone No.</th><th>Number of People</th><th>Number of Table(s)</th><th>Date</th><th>Time</th><th>Note</th><th>Status</th><th>User ID</th></tr>";
			$.each(data.output, function(i,obj) {
				  table_str = table_str + "<tr>";	
                  table_str = table_str + "<td>" + obj['reservation_id'] + "</td>" ;		
				  table_str = table_str + "<td>" + obj['reservation_name'] + "</td>" ;		
				  table_str = table_str + "<td>" + obj['reservation_phone'] + "</td>";
				  table_str = table_str + "<td>" + obj['reservation_people'] + "</td>";	
                  table_str = table_str + "<td>" + obj['reservation_tables'] + "</td>";	
                  table_str = table_str + "<td>" + obj['reservation_date'] + "</td>";	
                  table_str = table_str + "<td>" + obj['reservation_time'] + "</td>";	
                  table_str = table_str + "<td>" + obj['reservation_note'] + "</td>";	
                  table_str = table_str + "<td>" + obj['status'] + "</td>";	
                  table_str = table_str + "<td>" + obj['user_id'] + "</td>";	
				  table_str = table_str + "</tr>";					
			});
			table_str = table_str + "</table>";
			$("div#showreservations").html(table_str);
		})//.done(function(data)
		;//$.ajax({
	});//$("button#getReservations").click(function(){
	
	
	$("button#createReservation").click(function(){
			
		var url = "http://localhost/Web/Admin/WebService/food/";
		
		$.ajax({
			url: url,
			//accepts: "application/json",
			headers:{Accept:"application/json" },
			
			method: "POST", 
			data:{
					name: $("input#create_txt_name").val() , 
					phone: $("input#create_txt_phone").val() ,
					people: $("input#create_txt_people").val() ,
					date: $("input#create_txt_date").val() ,
                    time: $("input#create_txt_time").val() ,
					note: $("input#create_txt_note").val() ,
					status: $("input#create_txt_status").val() ,
					user_id: $("input#create_txt_user_id").val()
			},
			error: function(xhr){
      			
      				alert("An error occured: " + xhr.status + " " + xhr.statusText);
      		}
    	})
    	.done(function(data)
    	{	
			$("span#createresult").html("Reservations details added successfully");
		})//.done(function(data)
		;//$.ajax({
	});//$("button#createReservation").click(function(){

	$("button#updateReservation").click(function(){
		var reservationid = $("input#update_txt_reservationid").val();
		var url = "http://localhost/Web/Admin/WebService/food/";
		if(reservationid!= "" )
		{
			url= url + "?rid=" + reservationid;
		}
        $.ajax({
			url: url,
			headers:{Accept:"application/json" },
			
			method: "PUT", 
			data:{
                    name: $("input#update_txt_name").val() , 
					phone: $("input#update_txt_phone").val() ,
					people: $("input#update_txt_people").val() ,
					date: $("input#update_txt_date").val() ,
                    time: $("input#update_txt_time").val() ,
					note: $("input#update_txt_note").val() ,
					status: $("input#update_txt_status").val() ,
					user_id: $("input#update_txt_user_id").val()
			},
			error: function(xhr){
      			
      				alert("An error occured: " + xhr.status + " " + xhr.statusText);
      		}
    	})
		.done(function(data)
    	{	
			$("span#updateresult").html("Reservation details updated successfully");
		})//.done(function(data)
		;//$.ajax({
    });
    
    $("button#deleteReservation").click(function(){
		var reservationidd = $("input#delete_txt_reservationid").val();
		var url = "http://localhost/Web/Admin/WebService/food/";
		if(reservationidd!= "" )
		{
			url= url + "?rid=" + reservationidd;
		}
		$.ajax({
			url: url,
			//accepts: "application/json",
			headers:{Accept:"application/json" },
			
			method: "DELETE", 
			error: function(xhr){
      			
				  alert("An error occured: " + xhr.status + " " + xhr.statusText);
		  }
	})
	.done(function(data)
    	{	
			$("span#deleteresult").html("Reservation details deleted successfully");
		})//.done(function(data)
		;//$.ajax({

	
  });	//$(document).ready(function()

});
</script>
	
<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
th, td {
  padding: 5px;
}
</style>

</head>
<body>
<a href="reservation.php"><span class="material-icons-sharp" style="color:black"> arrow_back </span>
    <p>Back Home Page</p>
    </a>

<form>
<fieldset>
      <legend>Reservation List </legend>
       Optional Name to lookup:<input type="text" id="lkup_txt_name" ><br><br>
      <button type="button" id="getReservations">Get Reservations</button>
 <div id="showreservations">
 </div>

</fieldset>
<br>
<fieldset>
      <legend>Add Reservation</legend>
      Reservation Name:<input type="text" id="create_txt_name" ><br><br>
      Phone No.:<input type="text" id="create_txt_phone" ><br><br>
      Number of People:<input type="text" id="create_txt_people" ><br><br>
      Date:<input type="text" id="create_txt_date" ><br><br>
      Time:<input type="text" id="create_txt_time" ><br><br>
      Note:<input type="text" id="create_txt_note" ><br><br>
      Status:<input type="text" id="create_txt_status" ><br><br>
      User ID:<input type="text" id="create_txt_user_id" ><br><br>
      
      <button type="button" id="createReservation">Create Reservation</button>
 <span id="createresult">
 </span>
</fieldset>
<br>
<fieldset>
      <legend>Update Reservation</legend>
	  Update Reservation Details---- Enter Reservation ID:<input type="text" id="update_txt_reservationid" ><br><br>
      Reservation Name:<input type="text" id="update_txt_name" ><br><br>
      Phone No.:<input type="text" id="update_txt_phone" ><br><br>
      Number of People:<input type="text" id="update_txt_people" ><br><br>
      Date:<input type="text" id="update_txt_date" ><br><br>
      Time:<input type="text" id="update_txt_time" ><br><br>
      Note:<input type="text" id="update_txt_note" ><br><br>
      Status:<input type="text" id="update_txt_status" ><br><br>
      User ID:<input type="text" id="update_txt_user_id" ><br><br>
      
      <button type="button" id="updateReservation">Update Reservation</button>
 <span id="updateresult">
 </span>
</fieldset>
<br>
<fieldset>
      <legend>Delete Reservation</legend>
       Delete Reservation Details---- Enter Reservation ID: <input type="text" id="delete_txt_reservationid" ><br><br>
      <button type="button" id="deleteReservation">Delete Reservation</button>
	  <span id="deleteresult">
	  </span>

</fieldset>
 
 
</form> 
</body>		
</html>
	
	