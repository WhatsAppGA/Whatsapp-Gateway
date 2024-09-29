<div class="tab-pane fade " id="examplewebhook" role="tabpanel">
    <h3>Webhook Example</h3>
    <p>Webhook is a feature that allows you to receive a callback from our server when a message is incoming to your
        device.
        You can use this feature for made a dinamic chatbot or whatever you want. </p>
    <p>We will send a POST request to your webhook url with a JSON body. Here is an example of the JSON body we will
        send:</p>
    <pre class="bg-dark text-white">
        <code class="json">
{
  "message" : "message",
  "from" : "the number of the whatsapp sender",
  "bufferImage" : "base64 image, null if message not contain image",
}

        </code>
      </pre>
      <br>
      <p>For example webhook you can see in <a href="https://github.com/Ilmans/webhook-wamp-example.git" target="_blank"> <span class="badge bg-primary">Here</span></a></p>
      



</div>
