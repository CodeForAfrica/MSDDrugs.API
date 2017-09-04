<!-- Sorting section starts here -->
<section id="sorting-section" class="doc-section">
    <h2 class="section-title">Sorting</h2>
    <div class="section-block" style="">
        <p>Some endpoints offer result sorting, triggered using the <code>order_by</code> query parameter. You can specify descending sort by postpending <code>desc</code> to a field. Not all fields can be sorted on. The endpoint documentation will list supported sort options.</p>
<pre class="language-javascript">
<code>GET /api/drugs?order_by=name,asc
</code>
</pre>
    </div>
</section><!--//doc-section-->
<!-- Sorting section ends here -->