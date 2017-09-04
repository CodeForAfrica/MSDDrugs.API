<!-- Pagination section starts here -->
<section id="pagination-section" class="doc-section">
    <h2 class="section-title">Pagination</h2>
    <div class="section-block" style="">
        <p>Requests for collections can return between 0 and 100 results, controlled using the <code>limit</code> and <code>page</code> query parameters. All end points are limited to 10 results by default.</p>
<pre class="language-javascript">
<code>GET /api/drugs?lmit=5&amp;page=2
</code>
</pre>
        <p>Not all endpoints support pagination. If they do, it will be mentioned in their documentation.</p>
    </div>
</section><!--//doc-section-->
<!-- Pagination section ends here -->