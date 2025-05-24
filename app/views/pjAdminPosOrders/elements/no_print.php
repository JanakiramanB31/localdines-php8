
<div id="noPrint">
  <style media="print">
     @media print {
      @page { margin: 0; }
      body { margin: 1.6cm; }
    }
  </style>
  <div class="ticket">
    <div>
      <span>&nbsp;</span><br/><br/>
    </div>
  </div>
</div>
<script type="text/javascript">
  function printDiv(divName) {
    if (window.print) {
      var printContents = document.getElementById(divName).innerHTML;
      var originalContents = document.body.innerHTML;
      document.body.innerHTML = printContents;
      window.print();
      document.body.innerHTML = originalContents;
    }
  }
</script>