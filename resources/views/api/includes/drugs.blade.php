<section id="drugs-section" class="doc-section">
    <h2 class="section-title">Drugs</h2>
    <div class="section-block" style="">
      <p>
      All requests are tracked as drugs.
      </p>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
              <tr>
                  <th>Field</th>
                  <th>Type</th>
                  <th>Notes</th>
              </tr>
          </thead>
          <tbody>
              <tr>
                  <td>id</td>
                  <td>int</td>
                  <td>Associated drug</td>
              </tr>
              <tr>
                  <td>name</td>
                  <td>string</td>
                  <td>Drug name</td>
              </tr>
              <tr>
                  <td>form</td>
                  <td>string</td>
                  <td>Drug form</td>
              </tr>
              <tr>
                  <td>strength</td>
                  <td>string</td>
                  <td>Drug strength</td>
              </tr>
              <tr>
                  <td>uom</td>
                  <td>string</td>
                  <td>Drug unit of measure</td>
              </tr>
              <tr>
                  <td>price</td>
                  <td>string</td>
                  <td>Drug price</td>
              </tr>
              <tr>
                  <td>created_at</td>
                  <td>timestamp</td>
                  <td>Time when drug was added.</td>
              </tr>
              <tr>
                  <td>updated_at</td>
                  <td>timestamp</td>
                  <td>Time when drug details were last updated</td>
              </tr>
          </tbody>
        </table>
      </div><!--//table-responsive-->
    </div><!-- //section-block -->

    <div id="listing-drugs"  class="section-block">
        <h3 class="block-title">Listing drugs</h3>
<pre class="language-javascript">
<code>GET /api/drugs
</code>
</pre>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
              <tr>
                  <th>Query Parameter</th>
                  <th>Notes</th>
              </tr>
          </thead>
          <tbody>
              <tr>
                  <td>api_token</td>
                  <td><strong>NOT REQUIRED</strong></td>
              </tr>
              <tr>
                  <td>page</td>
                  <td>Page number, starting from 1</td>
              </tr>
              <tr>
                  <td>limit</td>
                  <td>Any integer between 0 and 100, inclusive</td>
              </tr>
              <tr>
                  <td>order_by</td>
                  <td>See <a href="#sorting-section">Sorting</a></td>
              </tr>
              <tr>
                  <td>columns</td>
                  <td>See <a href="#field-filtering-section">Column Filtering</a></td>
              </tr>
          </tbody>
        </table>
      </div><!--//table-responsive-->

      <h4>Sample Request</h4>
<pre class="language-javascript">
<code>GET /api/drugs?order_by=name,asc&limit=1
</code>
</pre>

      <h4>It's response</h4>
<pre class="language-javascript">
<code>
Status Code: 200 OK
Connection: Keep-Alive
Content-Length: 564
Content-Type: application/json; charset=utf-8
</code>
<hr />
<code>{
  "status": 200,
  "drugs": [
    {
      "id": 1,
      "name": "Acetylsalicylic ",
      "form": "Tablet",
      "strength": "300 mg",
      "uom": "1000 TABLETS",
      "price": "7,200",
      "created_at": null,
      "updated_at": null
    },
    {
      "id": 2,
      "name": "Adrenaline ",
      "form": "Injection",
      "strength": "1 mg/ml",
      "uom": "10 AMPOULES",
      "price": "8,900",
      "created_at": null,
      "updated_at": null
    }
  ]
}
</code>
</pre>
    </div><!--//section-block-->

    <div id="getting-drug"  class="section-block">
        <h3 class="block-title">Getting a single drug</h3>
<pre class="language-javascript">
<code>GET /api/drugs/id
</code>
</pre>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
              <tr>
                  <th>Query Parameter</th>
                  <th>Notes</th>
              </tr>
          </thead>
          <tbody>
              <tr>
                  <td>api_token</td>
                  <td><strong>NOT REQUIRED</strong></td>
              </tr>
          </tbody>
        </table>
      </div><!--//table-responsive-->

      <h4>Sample Request</h4>
<pre class="language-javascript">
<code>GET /api/drugs/11
</code>
</pre>

      <h4>It's response</h4>
<pre class="language-javascript">
<code>
Status Code: 200 OK
Connection: Keep-Alive
Content-Length: 272
Content-Type: application/json; charset=utf-8
</code>
<hr />
<code>{
    "drug":
    {
        "id": 11,
        "id": 1,
        "name": "Acetylsalicylic ",
        "form": "Tablet",
        "strength": "300 mg",
        "uom": "1000 TABLETS",
        "price": "7,200",
        "created_at": null,
        "updated_at": null
    }
}</code>
</pre>
    </div><!--//section-block-->

    <div id="updating-drug"  class="section-block">
        <h3 class="block-title">Updating drug</h3>
<pre class="language-javascript">
<code>PUT /api/drugs/id?api_token={YOUR_API_TOKEN_KEY}
</code>
</pre>

      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
              <tr>
                  <th>Field</th>
                  <th>Required</th>
                  <th>Notes</th>
              </tr>
          </thead>
          <tbody>
            <tr>
                <td>name</td>
                <td>no</td>
                <td>Set of characters representing drug name</td>
            </tr>
            <tr>
                <td>form</td>
                <td>no</td>
                <td>Set of characters representing drug form</td>
            </tr>
            <tr>
                <td>strength</td>
                <td>no</td>
                <td>Set of characters representing drug strength</td>
            </tr>
            <tr>
                <td>uom</td>
                <td>no</td>
                <td>Set of characters representing drug unit of measure</td>
            </tr>
            <tr>
                <td>price</td>
                <td>no</td>
                <td>Set of characters representing drug price</td>
            </tr>
          </tbody>
        </table>
      </div><!--//table-responsive-->

      <h4>Sample Request</h4>
<pre class="language-javascript">
<code>
PUT /api/drugs/11?api_token={YOUR_API_TOKEN_KEY}
Content-Type: application/json;
</code>
<hr />
<code>{
    "name":"Zinc Light",
    "form":"Liquid",
    "strength":"0.5 Litre",
    "uom":"2",
    "price":"4,200"
}
</code>
</pre>

      <h4>It's response</h4>
<pre class="language-javascript">
<code>
Status Code: 200 OK
Connection: Keep-Alive
Content-Length: 180
Content-Type: application/json; charset=utf-8
</code>
<hr />
<code>{
    "drug":
    {
        "id": 11,
        "name":"Zinc Light",
        "form":"Liquid",
        "strength":"0.5 Litre",
        "uom":"2",
        "price":"4,200"
        "created_at": "2017-08-16 07:54:34",
        "updated_at": "2017-08-16 11:54:18"
    }
}
</code>
</pre>
    </div><!--//section-block-->

    <div id="creating-drug"  class="section-block">
        <h3 class="block-title">Creating a new drug</h3>
<pre class="language-javascript">
<code>POST /api/drugs?api_token={YOUR_API_TOKEN_KEY}
</code>
</pre>

      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
              <tr>
                  <th>Field</th>
                  <th>Required</th>
                  <th>Notes</th>
              </tr>
          </thead>
          <tbody>
            <tr>
                <td>name</td>
                <td>yes</td>
                <td>Set of characters representing drug name</td>
            </tr>
            <tr>
                <td>form</td>
                <td>yes</td>
                <td>Set of characters representing drug form</td>
            </tr>
            <tr>
                <td>strength</td>
                <td>yes</td>
                <td>Set of characters representing drug strength</td>
            </tr>
            <tr>
                <td>uom</td>
                <td>yes</td>
                <td>Set of characters representing drug unit of measure</td>
            </tr>
            <tr>
                <td>price</td>
                <td>yes</td>
                <td>Set of characters representing drug price</td>
            </tr>
          </tbody>
        </table>
      </div><!--//table-responsive-->

      <h4>Sample Request</h4>
<pre class="language-javascript">
<code>
POST /api/drugs?api_token={YOUR_API_TOKEN_KEY}
Content-Type: application/json;
</code>
<hr />
<code>{
    "name":"Zinc Light",
    "form":"Liquid",
    "strength":"0.5 Litre",
    "uom":"2",
    "price":"4,200"
}
</code>
</pre>

        <h4>It's response</h4>
<pre class="language-javascript">
<code>
Status Code: 201 Created
Connection: Keep-Alive
Content-Length: 467
Content-Type: application/json; charset=utf-8
</code>
<hr />
<code>{
    "drug":
    {
        "name":"Zinc Light",
        "form":"Liquid",
        "strength":"0.5 Litre",
        "uom":"2",
        "price":"4,200"
        "updated_at": "2017-08-16 12:16:45",
        "created_at": "2017-08-16 12:16:45",
        "id": 12
    }
}
</code>
</pre>
    </div><!--//section-block-->

    <div id="removing-drug"  class="section-block">
        <h3 class="block-title">Removing drug</h3>
<pre class="language-javascript">
<code>DELETE /api/drugs/id?api_token={YOUR_API_TOKEN_KEY}
</code>
</pre>

        <h4>Sample Request</h4>
<pre class="language-javascript">
<code>
DELETE /api/drugs/11?api_token={YOUR_API_TOKEN_KEY}
</code>
</pre>

        <h4>It's response</h4>
<pre class="language-javascript">
<code>
Status Code: 200 OK
Connection: Keep-Alive
Content-Length: 643
Content-Type: application/json; charset=utf-8
</code>
<hr />
<code>{
  
}
</code>
</pre>
    </div><!--//section-block-->

    <div id="buying-price-drug"  class="section-block">
        <h3 class="block-title">Drug buying price</h3>
<pre class="language-javascript">
<code>GET /api/drugs?name={name*}&buyin_price={price}
</code>
</pre>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
              <tr>
                  <th>Query Parameter</th>
                  <th>Notes</th>
              </tr>
          </thead>
          <tbody>
              <tr>
                  <td>api_token</td>
                  <td><strong>NOT REQUIRED</strong></td>
              </tr>
              <tr>
                  <td>name</td>
                  <td>Drug name followed with a <strong>*</strong></td>
              </tr>
              <tr>
                  <td>buying_price</td>
                  <td>Amount which user buy the drug.</td>
              </tr>
          </tbody>
        </table>
      </div><!--//table-responsive-->

      <h4>Sample Request</h4>
<pre class="language-javascript">
<code>GET /api/drugs?name=zin*&buying_price=7000
</code>
</pre>

      <h4>It's response</h4>
<pre class="language-javascript">
<code>
Status Code: 200 OK
Connection: Keep-Alive
Content-Length: 272
Content-Type: application/json; charset=utf-8
</code>
<hr />
<code>{
  "status": 200,
  "drug": {
    "id": 215,
    "name": "Zinc Sulphate",
    "form": "Tablet",
    "strength": "20 mg",
    "uom": "100 TABLETS",
    "price": "6,300",
    "created_at": null,
    "updated_at": null
  },
  "price_check": {
    "buying_price_status": "above",
    "extra_amount": 700
  }
}</code>
</pre>
    </div><!--//section-block-->
</section><!--//doc-section-->

 