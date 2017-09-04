<!-- Responses section starts here -->
<section id="responses-section" class="doc-section">
    <h2 class="section-title">Responses</h2>
    <div class="section-block" style="">
        <p>All response bodies are <a href="http://en.wikipedia.org/wiki/JSON">JSON</a> encoded.</p>
      	<p>A single resource is represented as a JSON object:</p>
<pre class="language-javascript">
<code>{
  "field1": "value",
  "field2": true,
  "field3": []
}
</code>
</pre>

      	<p>A collection of resources is represented as a JSON array of objects:</p>
<pre class="language-javascript">
<code>[
  {
    "field1": "value",
    "field2": true,
    "field3": []
  },
  {
    "field1": "another value",
    "field2": false,
    "field3": []
  }
]
</code>
</pre>

		<p>Timestamps are in <a href="http://en.wikipedia.org/wiki/Coordinated_Universal_Time">UTC</a> and formatted as <a href="http://en.wikipedia.org/wiki/ISO_8601">ISO8601</a>.</p>
      	<p>Unset fields will be represented as a <code>null</code> instead of not being present.  If the field is an array, it will be represented as an empty array - ie <code>[]</code>.</p>

      	<h3>HTTP Status Codes</h3>

      	<p>We use HTTP status codes to indicate success or failure of a request.</p>

      	<p>Success codes:</p>

	    <ul>
	        <li>
	          <code>200 OK</code> - Request succeeded. Response included
	        </li>
	        <li>
	          <code>201 Created</code> - Resource created. URL to new resource in Location header
	        </li>
	        <li>
	          <code>204 No Content</code> - Request succeeded, but no response body
	        </li>
	    </ul>

      	<p>Error codes:</p>

	    <ul>
	        <li>
	          <code>400 Bad Request</code> - Could not parse request
	        </li>
	        <li>
	          <code>401 Unauthorized</code> - No authentication credentials provided or authentication failed
	        </li>
	        <li>
	          <code>403 Forbidden</code> - Authenticated user does not have access
	        </li>
	        <li>
	          <code>404 Not Found</code> - Resource not found
	        </li>
	        <li>
	          <code>415 Unsupported Media Type</code> - POST/PUT/PATCH request occurred without a <code>application/json</code> content type
	        </li>
	        <li>
	          <code>422 Unprocessable Entry</code> - A request to modify or create a resource failed due to a <a href="#validation">validation error</a>
	        </li>
	        <li>
	          <code>429 Too Many Requests</code> - Request rejected due to <a href="#ratelimit">rate limiting</a>
	        </li>
	        <li>
	          <code>500, 501, 502, 503, etc</code> - An internal server error occured
	        </li>
	    </ul>

      	<h3>Errors</h3>

      	<p>All 400 series errors (400, 401, 403, etc) will be returned with a JSON object in the body and a <code>application/json</code> content type.</p>

<pre class="language-javascript">
<code>{
  "message": "Not Found"
}
</code>
</pre>

      	<p>500 series error codes (500, 501, 502, etc) do not return JSON bodies.</p>

      	<h3>Validation Errors</h3>

      	<p>In case of validation errors on a POST/PUT/PATCH request, a <code>422 Unprocessable Entry</code> status code will be returned.  The JSON response body will include an array of error messages.</p>

<pre class="language-javascript">
<code>{
  "message": "Validation Failed",
  "errors": [
    {
      "message": "Field is not valid"
    },
    {
      "message": "OtherField is already used"
    }
  ]
}
</code>
</pre>
    </div>
</section><!--//doc-section-->
<!-- Responses section ends here -->