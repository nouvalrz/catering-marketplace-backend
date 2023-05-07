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
            MidtransBack.postMessage(true);
        },
        onSuccess: function (result){
            console.log("berhasil nih")
        },
        onClose: function (result){
            console.log("diclose nih")
        },
    });



    // var observer = new MutationObserver(function(mutations) {
    //     var backToMerchantButton = document.querySelector('.card-pay-button-part')
    //     if (backToMerchantButton) {
    //         backToMerchantButton.addEventListener('click', function (){
    //
    //             console.log("HIT")
    //         });
    //         observer.disconnect();
    //     }
    // });
    //
    // observer.observe(document.body, {attributes: false, childList: true, characterData: false, subtree:true});

</script>
</body>
</html>
