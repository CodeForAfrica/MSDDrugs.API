<!-- Request section starts here -->
<section id="requests-section" class="doc-section">
    <h2 class="section-title">Requests</h2>
    <div class="section-block">
        <p>
        	The base URL of the API is http://htools-tz-msd-drug-tool-api.dokku-1.codefortanzania.org/api to access and send your requests.
        </p>

        <h3>JSON Bodies</h3>
        <p>
        	All POST, PUT, PATCH requests are JSON encoded and must have have content type of application/json, or the API will return a 415 Unsupported Media Type status code.
        </p>

        <div class="code-block">
<pre>
<code class="language-javascript">$ curl http://htools-tz-msd-drug-tool-api.dokku-1.codefortanzania.org/api/drugs/23 \
-H 'Content-Type: application/json' \
-d '{"name": "Zinc Sulphate",
      "form": "Tablet",
      "strength": "20 mg",
      "uom": "100 TABLETS",
      "price": "6,300",
	}'
</code>
</pre>
	   	</div>

        <h3>HTTP Verbs</h3>
        <p>
        	The API uses the standard HTTP verbs to indicate intent of a request:
        </p>
        <ul>
	        <li>
	          <code>GET</code> - To retrieve a resource or a collection of resources
	        </li>
	        <li>
	          <code>POST</code> - To create a resource
	        </li>
	        <li>
	          <code>PATCH</code> - To modify a resource
	        </li>
	        <li>
	          <code>PUT</code> - To set a resource
	        </li>
	        <li>
	          <code>DELETE</code> - To delete a resource
	        </li>
	    </ul>

	    <h3>Limited HTTP Clients</h3>

      	<p>If you are using an HTTP client that doesn't support <code>PUT</code>, <code>PATCH</code> or <code>DELETE</code> requests, send a <code>POST</code> request with an <code>X-HTTP-Method-Override</code> header specifying the desired verb.</p>

<pre>
<code class="language-javascript">$ curl http://htools-tz-msd-drug-tool-api.dokku-1.codefortanzania.org/api/drugs/23 \
-X POST \
-H "X-HTTP-Method-Override: DELETE"
</code>
</pre>
    </div>
</section><!--//doc-section-->
<!-- Requests section ends here -->