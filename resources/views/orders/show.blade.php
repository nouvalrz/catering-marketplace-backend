<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>


<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    snap.pay('{{ $snapToken }}', {
        onPending: function (result){
            console.log("Udah milih metode nih")
        },
        onSuccess: function (result){
            console.log("berhasil nih")
        }
    });
</script>
</body>
</html>
