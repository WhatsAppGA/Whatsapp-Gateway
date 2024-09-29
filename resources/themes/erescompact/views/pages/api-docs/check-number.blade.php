<div class="tab-pane fade " id="checknumber" role="tabpanel">
    <h3>Check Number API</h3>
    <p>Method : <code class="text-success">POST</code> | <code class="text-primary">GET</code></p>
    <p>Endpoint: <code>{{ env('APP_URL') }}/check-number</code></p>

    <p>Request Body : (JSON If POST) </p>
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
            <tr>
                <td>api_key</td>
                <td>string</td>
                <td>Yes</td>
                <td>API Key</td>
            </tr>
			<tr>
                <td>sender</td>
                <td>string</td>
                <td>Yes</td>
                <td>your number ex 62888xxxx</td>
            </tr>
            <tr>
                <td>number</td>
                <td>string</td>
                <td>Yes</td>
                <td>any number ex 62888xxxx</td>
            </tr>
        </tbody>
    </table>
    <br>
    <p>Examplo JSON Request</p>
    <pre class="bg-dark text-white">
      <code>
    {
	  "status": true,
	  "msg": {
		"exists": true,
		"jid": "201111347197@s.whatsapp.net"
	  }
	}
      </code>
      </pre>
    <p>Example URL Request</p>
    <pre class="bg-dark text-white">
        <code class="json">
{{ env('APP_URL') }}/check-number?api_key=1234567890&sender=6281222xxxxx&number=6281222xxxxx
        </code>
      </pre>


</div>
