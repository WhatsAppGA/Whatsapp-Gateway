<div class="tab-pane fade e" id="generateqr" role="tabpanel">
    <h3>Generate QR API</h3>
    <p>Method : <code class="text-success">POST</code>
    <p>Endpoint: <code>{{ env('APP_URL') }}/generate-qr</code></p>

    <p>Request Body : (JSON If POST)
    <table class="table">
        <thead>
            <tr>
                <th>Parameter</th>
                <th>Type</th>
                <th>Required</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            {{-- {
            "device" : "6282298859671",
            "api_key" : "ndUJR38JkvyCfLZ"
  } --}}
            <tr>
                <td>device</td>
                <td>string</td>
                <td>Yes</td>
                <td>Number of your device</td>

            </tr>
            <tr>
                <td>api_key</td>
                <td>string</td>
                <td>Yes</td>
                <td>API Key</td>
            </tr>
            <tr>
                <td>force</td>
                <td>boolean</td>
                <td>no</td>
                <td>If true, when device is not exist, it will be created</td>
            </tr>

        </tbody>
    </table>
    <br>
    <p>Normal Response</p>
    <pre class="bg-dark text-white">
      <code>
{
    "status": "processing",
    "message": "Processing"
}

// if processing like above,you need to hit the endpoint again to get the result
// and result will be like this
{
    "status": false,
    "qrcode": "data:image/png;base64,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",
    "message": "Please scann qrcode"
}

// the qrcode is base64 encoded.
// after scanned the qrcode, you need to hit the endpoint again to get last response.

// if already scanned, the response will be like this:
{
    "status": false,
    "msg": "Device already connected!"
}

      </code>
      </pre>
    <p>Failed response</p>
    <pre class="bg-dark text-white">
        <code class="json">
{
    "status": false,
    "msg": "Invalid data!",
    "errors": {} // list of errors
}
        </code>
      </pre>


</div>
