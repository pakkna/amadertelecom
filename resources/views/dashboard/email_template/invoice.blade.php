<html>

<body style="background-color:#e2e1e0;font-family: Open Sans, sans-serif;font-size:100%;font-weight:400;line-height:1.4;color:#000;">
  <table style="max-width:1080px;margin:50px auto 10px;background-color:#fff;padding:50px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px;-webkit-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);-moz-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24); border-top: solid 10px green;">
    <thead>
      <tr>
        <th style="text-align:left;">www.Getacleaner.ca</th>
        <th style="text-align:right;font-weight:400;">{{ date('d F, Y g:i A') }}</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="height:35px;"></td>
      </tr>
      <tr>
        <td colspan="2" style="border: solid 1px #ddd; padding:10px 20px;">
          <p style="font-size:14px;margin:0 0 6px 0;"><span style="font-weight:bold;display:inline-block;min-width:150px">Task status</span><b style="color:green;font-weight:normal;margin:0">{{ $TaskMaster->TaskStatus }}</b></p>
          <p style="font-size:14px;margin:0 0 6px 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Invoice ID : </span>{{ $InvoiceInfo->InvoiceNumber}}</p>
          <p style="font-size:14px;margin:0 0 6px 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Payment Status : </span>{{ $InvoiceInfo->Status }}</p>
          <p style="font-size:14px;margin:0 0 6px 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Total Vat  : </span>${{ $InvoiceInfo->TotalVAT }}</p>
          <p style="font-size:14px;margin:0 0 0 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Total Amount : </span> ${{ $InvoiceInfo->GrossRate }}</p>
        </td>
      </tr>
      <tr>
        <td style="height:35px;"></td>
      </tr>
      <tr>
        <td style="width:50%;padding:20px;vertical-align:top">
          <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px">Name</span> {{ $CustomerInfo->CustomerName}}</p>
          <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Email</span> {{$CustomerInfo->Email}}</p>
          <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Phone</span> {{$CustomerInfo->Mobile }}</p>
          <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Task Hours</span>{{$TaskMaster->TotalAssignedHour}} Hour</p>
        </td>
        <td style="width:50%;padding:20px;vertical-align:top">
          <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Home Address</span> {{ $CustomerInfo->HomeAddress }}</p>
          <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Cleaning Type </span> {{$TaskMaster->ServiceTypeName}}</p>
          <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Working Date</span>{{ date_format( date_create($TaskMaster->StartDate), 'd F, Y') }} </p>
        </td>
      </tr>
      <tr>
        <td colspan="2" style="font-size:20px;padding:30px 15px 0 15px;">Item Summery</td>
      </tr>
      <tr>
        <td colspan="2" style="padding:15px;">
          @foreach ($ItemDetails as $item)
            <p style="font-size:14px;margin:0;padding:10px;border:solid 1px #ddd;font-weight:bold;">
                <span style="display:block;font-size:13px;font-weight:normal;">{{ $item->ItemName}} -<small>{{ $item->ItemDetailTitle }}</small></span> Rate: ${{ $item->TotalRate }}<b style="font-size:12px;font-weight:300;margin-right:10px">Quantity :{{ $item->Quantity }}</b>
            </p>
          @endforeach
          <p style="font-size:14px;margin:0;padding:10px;border:solid 1px #ddd;font-weight:bold;"><span style="display:block;font-size:13px;font-weight:normal;">Total Amount (Vat) :</span> ${{ $InvoiceInfo->GrossRate}}</p>
        </td>
      </tr>
    </tbody>
    <tfooter>
      <tr>
        <td colspan="2" style="font-size:14px;padding:50px 15px 0 15px;">
          <strong style="display:block;margin:0 0 10px 0;">Regards</strong> Getacleaner Team<br>
          246-9 Crescent Pl , East York, ON, M4C 5L8<br><br>
          <b>Phone:</b> +1 437 230 3216<br>
          <b>Email:</b> Support@getacleaner.ca
        </td>
      </tr>
    </tfooter>
  </table>
</body>

</html>
