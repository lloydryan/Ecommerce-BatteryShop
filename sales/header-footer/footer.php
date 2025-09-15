<style>
  .container2 {
    background: #1A1A2E; /* Match header color */
    color: white;
    padding: 50px 5%;
    width: 100%;
    text-align: center;
    margin-top: 0;
  }

  .row1 {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
  }

  .column1, .column2 {
    flex: 1;
    min-width: 300px;
    padding: 20px;
  }

  .column1 h1, .column2 h1 {
    font-size: 24px;
    color: #FF9333; /* Highlighted header text */
  }

  .column1 p, .column2 p {
    font-size: 16px;
    color: #ddd;
  }

  .icons {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-top: 15px;
  }

  .icons i {
    transition: transform 0.3s, color 0.3s;
  }

  .icons i:hover {
    transform: scale(1.2);
    color: #FF9333 !important;
  }

  #vl {
    width: 2px;
    background: #FF9333;
    height: auto; /* Allows it to stretch dynamically */
    align-self: stretch; /* Makes it take the full height of the parent row */
    margin: 0 30px;
  }




  textarea {
    width: 100%;
    padding: 12px;
    border-radius: 5px;
    border: none;
    resize: none;
    font-size: 16px;
  }

  .footerbtn {
    background: #FF9333;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
  }

  .footerbtn:hover {
    background: #e07b2e;
  }

  .dev {
    text-align: center;
    font-size: 14px;
    color: #bbb;
    margin-top: 20px;
  }
</style>

<div class="container2">
  <div class="row1">
    <div class="column1" data-aos="fade-left">
      <h1>Connect With Us</h1> 
      <p>We're always looking to connect with <br> those who share interest in Icecream-man.</p>
      <div class="icons">
        <i class="fa-brands fa-facebook fa-2x" style="color: #1877F2;"></i>
        <i class="fa-brands fa-telegram fa-2x" style="color: #0088CC;"></i>
        <i class="fa-brands fa-facebook-messenger fa-2x" style="color: #006AFF;"></i>
        <i class="fa-brands fa-twitter fa-2x" style="color: #1DA1F2;"></i>
        <i class="fa-brands fa-discord fa-2x" style="color: #5865F2;"></i>
      </div>
    </div>

    <div id="vl"></div>

    <div class="column2" data-aos="fade-right">
      <h1>Contact Us <i class="fa-solid fa-paper-plane fa-1x"></i></h1>
      <p>We at Icecream-man highly value your suggestions and feedback. <br> Message us now!</p>
      <form action="#">
        <textarea name="message" id="message" cols="5" rows="3" placeholder="Message"></textarea>
        <hr>
        <button type="submit" class="footerbtn">SEND</button>
      </form>
    </div>
  </div>

  <hr style="border-color: #444;">

  <div class="dev">
    <p>Developers: Lloyd Ryan Largo | Mark Gaudicos <br> <i class="fa-regular fa-copyright"></i> 2023 IcecreamMan Inc.</p>
  </div>
</div>
