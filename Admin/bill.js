
   {/* Function to handle click event for "Details" links */}
    document.querySelectorAll('.details-link').forEach(link => {
    link.addEventListener('click', function (event) {
      event.preventDefault(); // Prevent default link behavior

      // Get the bill ID from the data attribute
      const billId = this.getAttribute('data-bill-id');

      // Use AJAX to fetch the bill details
      const xhr = new XMLHttpRequest();
      xhr.open('GET', `fetch_bill_details.php?bill_id=${billId}`, true);

      xhr.onload = function () {
        if (xhr.status === 200) { //request successfull
          // Insert the response (bill details) into the details container
          document.getElementById('bill-details').innerHTML = xhr.responseText;
        } else {
          alert('Error fetching bill details.');
        }
      };

      xhr.send();
    });
  });









//   document.querySelectorAll('.details-link'): This function selects all HTML elements with the class .details-link on the page. It's expected that each "Details" link has this class so they can be identified.

// .forEach(link => {...}): The .forEach() method is used to loop through each element that was selected. In this case, it's looping through each "Details" link to attach an event listener to it.

// link.addEventListener('click', ...): This adds a click event listener to each "Details" link. This means that when the user clicks on any of the "Details" links, the function inside the event listener will be triggered.

// event.preventDefault(): This function call prevents the default behavior of the link. Normally, clicking an anchor (<a>) tag would navigate the user to a new page or section. By using preventDefault(), it prevents this default behavior so we can handle the event programmatically (via AJAX in this case) instead of letting the browser follow the link.

// this: In the context of an event listener, this refers to the element that was clicked on (in this case, the "Details" link).

// getAttribute('data-bill-id'): This retrieves the value of the data-bill-id attribute from the clicked element. It is expected that each "Details" link has a data-bill-id attribute (e.g., <a href="#" class="details-link" data-bill-id="123">Details</a>). The value of data-bill-id (in this case, 123) is the bill ID that is passed to the server to fetch the corresponding details.

// new XMLHttpRequest(): This creates a new XMLHttpRequest object, which is used to send and receive data from a server asynchronously.

// xhr.open('GET', ...): The open() method configures the request. Here:

// 'GET': Specifies that we are making a GET request.

// `fetch_bill_details.php?bill_id=${billId}`: This is the URL that the request is sent to. The backticks (`) allow for template literals, meaning ${billId} is dynamically replaced with the actual bill ID retrieved earlier. So, for example, if the billId is 123, the request URL becomes fetch_bill_details.php?bill_id=123.

// true: Specifies that the request should be asynchronous.

// xhr.onload = function () {...}: This defines what should happen once the server has responded to the AJAX request. This event is triggered when the request is complete, and the server has sent a response.

// if (xhr.status === 200): This checks if the request was successful. A status code of 200 means the request was successful, and the server returned the requested data (in this case, bill details).

// document.getElementById('bill-details').innerHTML = xhr.responseText;:

// xhr.responseText contains the response data from the server (the details of the bill).
// This line of code inserts that response (the bill details) into the element with the ID bill-details. For example, this could be a <div id="bill-details"></div> in your HTML where the bill details are displayed.
// else { alert('Error fetching bill details.'); }: If the status code is not 200, it shows an alert to the user, indicating that there was an error in fetching the bill details.

// xhr.send(): This actually sends the AJAX request to the server. The server will process the request and return a response, which will be handled by the onload function.
