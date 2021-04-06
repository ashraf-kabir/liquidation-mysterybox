<style>
@media (max-width: 700px) {
  html {
    font-size: 80.5%;
  }
}

.checkout-section {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  max-width: 1350px;
  margin: 0 auto;
  padding: 3rem 2rem;
}

@media (max-width: 700px) {
  .checkout-section {
    padding: 2rem 1rem;
  }
}

@media (max-width: 992px) {
  .checkout-section {
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
        -ms-flex-direction: column;
            flex-direction: column;
  }
}

.checkout-section p,
.checkout-section h1,
.checkout-section h2,
.checkout-section h3,
.checkout-section h4,
.checkout-section h5,
.checkout-section h5 {
  margin: 0;
  padding: 0;
}

.checkout-section p {
  font-weight: normal;
}

.checkout-section .checkout-left {
  width: 70%;
  margin-right: 20px;
}

@media (max-width: 992px) {
  .checkout-section .checkout-left {
    width: 100%;
    margin-right: 0px;
  }
}

.checkout-section .checkout-left .checkout-row {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-pack: justify;
      -ms-flex-pack: justify;
          justify-content: space-between;
  padding: 15px 10px;
  border-bottom: 1px solid grey;
}

@media (max-width: 450px) {
  .checkout-section .checkout-left .checkout-row {
    -ms-flex-wrap: wrap;
        flex-wrap: wrap;
  }
}

.checkout-section .checkout-left .checkout-row .first-box {
  font-weight: bold;
  margin-right: 40px;
}

@media (max-width: 700px) {
  .checkout-section .checkout-left .checkout-row .first-box {
    margin-right: 20px;
  }
}

@media (max-width: 450px) {
  .checkout-section .checkout-left .checkout-row .first-box {
    width: 100%;
    margin-bottom: 0.5rem;
  }
}

.checkout-section .checkout-left .checkout-row .first-box span {
  text-transform: capitalize;
}

.checkout-section .checkout-left .checkout-row .first-box span:nth-child(1) {
  margin-right: 20px;
}

@media (max-width: 700px) {
  .checkout-section .checkout-left .checkout-row .first-box span:nth-child(1) {
    margin-right: 10px;
  }
}

.checkout-section .checkout-left .checkout-row .second-box {
  -webkit-box-flex: 1;
      -ms-flex: 1;
          flex: 1;
}

.checkout-section .checkout-left .checkout-row .third-box button {
  text-transform: capitalize;
}

.checkout-section .checkout-left .checkout-row .third-box .dropdown-box {
  height: 100vh;
  width: 100%;
  position: fixed;
  border: 1px solid grey;
  padding: 2rem;
  top: 0;
  left: 0;
  background-color: rgba(0, 0, 0, 0.9);
  z-index: 100;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
      -ms-flex-align: center;
          align-items: center;
  -webkit-box-pack: center;
      -ms-flex-pack: center;
          justify-content: center;
  display: none;
}

@media (max-width: 1350px) {
  .checkout-section .checkout-left .checkout-row .third-box .dropdown-box {
    overflow: scroll;
  }
}

.checkout-section .checkout-left .checkout-row .third-box .dropdown-box .modal-container {
  background-color: white;
  padding: 2rem;
  border-radius: 5px;
}

.checkout-section .checkout-left .checkout-row .third-box .dropdown-box .modal-container .payments-details {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
}

@media (max-width: 1350px) {
  .checkout-section .checkout-left .checkout-row .third-box .dropdown-box .modal-container .payments-details {
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
        -ms-flex-direction: column;
            flex-direction: column;
  }
}

.checkout-section .checkout-left .checkout-row .third-box .dropdown-box .modal-container .payments-details .account-details {
  padding-right: 2rem;
  border-right: 1px solid grey;
}

@media (max-width: 1350px) {
  .checkout-section .checkout-left .checkout-row .third-box .dropdown-box .modal-container .payments-details .account-details {
    padding-right: 0;
    padding-bottom: 2rem;
    border-right: none;
    border-bottom: 1px solid grey;
  }
}

.checkout-section .checkout-left .checkout-row .third-box .dropdown-box .modal-container .payments-details .billing-address {
  padding-left: 2rem;
}

@media (max-width: 1350px) {
  .checkout-section .checkout-left .checkout-row .third-box .dropdown-box .modal-container .payments-details .billing-address {
    padding-left: 0;
    padding-top: 2rem;
  }
}

.checkout-section .checkout-left .checkout-row .third-box .dropdown-box .modal-container .heading {
  text-align: center;
  margin-bottom: 2rem;
}

.checkout-section .checkout-left .checkout-row .third-box .dropdown-box .modal-container .inputs-container {
  -webkit-box-flex: 1;
      -ms-flex: 1;
          flex: 1;
}

.checkout-section .checkout-left .checkout-row .third-box .dropdown-box .modal-container .inputs-container > div {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
      -ms-flex-align: center;
          align-items: center;
  -webkit-box-pack: justify;
      -ms-flex-pack: justify;
          justify-content: space-between;
  margin: 1rem 0;
}

.checkout-section .checkout-left .checkout-row .third-box .dropdown-box .modal-container .inputs-container label {
  min-width: 100px;
  text-transform: capitalize;
}

.checkout-section .checkout-left .checkout-row .third-box .dropdown-box .modal-container .inputs-container input {
  padding: 1rem;
  outline: none;
  width: 500px;
}

@media (max-width: 1350px) {
  .checkout-section .checkout-left .checkout-row .third-box .dropdown-box .modal-container .inputs-container input {
    width: 300px;
  }
}

@media (max-width: 450px) {
  .checkout-section .checkout-left .checkout-row .third-box .dropdown-box .modal-container .inputs-container input {
    width: 200px;
  }
}

.checkout-section .checkout-left .checkout-row .third-box .dropdown-box .modal-container .checkout-info-add-btn {
  text-align: right;
}

.checkout-section .checkout-left .checkout-row .third-box .dropdown-box.active {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
}

@media (max-width: 1350px) {
  .checkout-section .checkout-left .checkout-row .third-box .dropdown-box.active {
    display: -ms-inline-grid;
    display: inline-grid;
  }
}

@media (max-width: 650px) {
  .checkout-section .checkout-left .checkout-row .third-box .dropdown-box.active {
    display: block;
  }
}

.checkout-section .checkout-right {
  width: 30%;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-pack: start;
      -ms-flex-pack: start;
          justify-content: flex-start;
  -webkit-box-align: start;
      -ms-flex-align: start;
          align-items: flex-start;
}

@media (max-width: 992px) {
  .checkout-section .checkout-right {
    width: 100%;
  }
}

.checkout-section .checkout-right .box {
  border: 1px solid grey;
  border-radius: 3px;
  padding: 10px;
  max-width: 300px;
  position: -webkit-sticky;
  position: sticky;
  top: 0;
}

@media (max-width: 992px) {
  .checkout-section .checkout-right .box {
    max-width: 100%;
    position: relative;
    margin: 0 auto;
    margin-top: 2rem;
  }
}

.checkout-section .checkout-right .box .header button {
  padding: 10px;
  width: 100%;
  text-transform: capitalize;
  font-weight: bold;
  font-size: 1.2rem;
  margin-top: 2rem;
}

@media (max-width: 700px) {
  .checkout-section .checkout-right .box .header button {
    font-size: 1.4rem;
  }
}

.checkout-section .checkout-right .box .header p {
  padding: 5px 0 10px 0;
  font-size: 14px;
  text-align: center;
}

.checkout-section .checkout-right .box .summary > p {
  font-size: 20px;
  font-weight: bold;
  margin-bottom: 10px;
  text-transform: capitalize;
}

.checkout-section .checkout-right .box .summary p {
  font-size: 16px;
}

.checkout-section .checkout-right .box .summary .details > div {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-pack: justify;
      -ms-flex-pack: justify;
          justify-content: space-between;
  margin: 5px 0;
  text-transform: capitalize;
}

.checkout-section .checkout-right .box .summary .details > div:nth-child(2) p:nth-child(2) {
  min-width: 70px;
  text-align: right;
  padding-bottom: 5px;
  border-bottom: 1px solid rgba(128, 128, 128, 0.5);
}

.checkout-section .checkout-right .box .summary .details > div:nth-child(4) {
  padding-bottom: 5px;
  border-bottom: 1px solid rgba(128, 128, 128, 0.5);
}

.checkout-section .checkout-right .box .summary .details > div:nth-child(5) {
  font-size: 20px;
  font-weight: bold;
  color: red;
}

.checkout-row#review-and-shipping {
  border-bottom: none;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
      -ms-flex-direction: column;
          flex-direction: column;
}

.checkout-row#review-and-shipping .first-box {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  margin-right: 0;
}

.checkout-row#review-and-shipping .first-box p {
  text-transform: capitalize;
  font-weight: bold;
}

.checkout-row#review-and-shipping .box {
  margin-top: 20px;
  border: 1px solid rgba(128, 128, 128, 0.5);
  padding: 10px 20px;
  border-radius: 5px;
}

@media (max-width: 700px) {
  .checkout-row#review-and-shipping .box {
    padding: 10px;
  }
}

.checkout-row#review-and-shipping .box .heading {
  text-align: center;
  margin-bottom: 20px;
}

.checkout-row#review-and-shipping .box .heading h3 {
  font-size: 22px;
  text-transform: capitalize;
  margin-bottom: 10px;
}

@media (max-width: 700px) {
  .checkout-row#review-and-shipping .box .heading h3 {
    font-size: 18px;
  }
}

.checkout-row#review-and-shipping .box .heading p {
  font-size: 16px;
}

@media (max-width: 700px) {
  .checkout-row#review-and-shipping .box .heading p {
    font-size: 14px;
  }
}

.checkout-row#review-and-shipping .box .product {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
      -ms-flex-align: center;
          align-items: center;
  margin: 20px 0;
}

@media (max-width: 450px) {
  .checkout-row#review-and-shipping .box .product {
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
        -ms-flex-direction: column;
            flex-direction: column;
  }
  .checkout-row#review-and-shipping .box .product:not(:last-child) {
    padding-bottom: 1rem;
    border-bottom: 1px solid grey;
  }
}

.checkout-row#review-and-shipping .box .product .image {
  margin-right: 20px;
}

@media (max-width: 700px) {
  .checkout-row#review-and-shipping .box .product .image img {
    width: 70px;
    height: 70px;
  }
}

@media (max-width: 450px) {
  .checkout-row#review-and-shipping .box .product .image img {
    width: 100px;
    height: 100px;
    margin-bottom: 1rem;
  }
}

.checkout-row#review-and-shipping .box .product .details h4 {
  font-size: 20px;
}

@media (max-width: 700px) {
  .checkout-row#review-and-shipping .box .product .details h4 {
    font-size: 18px;
  }
}

.checkout-row#review-and-shipping .box .product .details .product-quantity {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
      -ms-flex-align: center;
          align-items: center;
  margin: 5px 0;
}

.checkout-row#review-and-shipping .box .product .details .product-quantity button {
  padding: 0 10px;
  margin: 0 10px;
}

.checkout-row#review-and-shipping .box .product .details .shipping-cost {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  margin: 5px 0;
  -webkit-box-align: center;
      -ms-flex-align: center;
          align-items: center;
}

.checkout-row#review-and-shipping .box .product .details .shipping-cost p:nth-child(2) {
  margin: 0 1rem;
}

.checkout-row#review-and-shipping .box .product .details .shipping-cost select {
  margin-right: 10px;
}
</style>

<section class="checkout-section" id="checkout-section">
			<div class="checkout-left">
				<div class="checkout-row">
					<div class="first-box">
						<span>1</span>
						<span>Shipping Address</span>
					</div>
					<div class="second-box">
						<p>521 Chardonnay Drive</p>
						<p>Langley, VA</p>
						<p>Washington</p>
					</div>
					<div class="third-box">
						<button class="dropdown-btn btn btn-secondary">change/add</button>
						<div class="dropdown-box">
							<div class="modal-container">
								<div class="shipping-address">
									<div class="heading">Add shipping Address</div>
									<div class="inputs-container">
										<div>
											<label for="address">Address:</label>
											<input name="address" type="text" placeholder="your address" />
										</div>
										<div>
											<label for="country">Country:</label>
											<input name="country" type="text" placeholder="your country" />
										</div>
										<div>
											<label for="city">City:</label>
											<input name="city" type="text" placeholder="your city" />
										</div>
										<div>
											<label for="state">State:</label>
											<input name="state" type="text" placeholder="your state" />
										</div>
										<div>
											<label for="zip-code">Zip-Code:</label>
											<input name="zip-code" type="text" placeholder="your zip-code" />
										</div>
									</div>
								</div>

								<div class="checkout-info-add-btn">
									<button class="close-btn btn btn-secondary">Close</button>
									<button class="btn btn-primary">Save</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="checkout-row">
					<div class="first-box">
						<span>2</span>
						<span>payment method</span>
					</div>
					<div class="second-box">
						<p>visa expires in 3days</p>
						<p>
							<span>Billing Address:</span>
							<span>Lorem, ipsum dolor.</span>
						</p>
						<!-- <p>lorem psum</p> -->
					</div>
					<div class="third-box">
						<button class="dropdown-btn btn btn-secondary">change/add</button>
						<div class="dropdown-box">
							<div class="modal-container">
								<div class="payments-details">
									<div class="account-details">
										<div class="heading">Add Payement Details</div>
										<div class="inputs-container">
											<div>
												<label for="account-no">account-no:</label>
												<input name="account-no" type="text" placeholder="your account-no" />
											</div>
											<div>
												<label for="month">month:</label>
												<input name="month" type="text" placeholder="your month" />
											</div>
											<div>
												<label for="year">year:</label>
												<input name="year" type="text" placeholder="your year" />
											</div>
											<div>
												<label for="CVC">CVC:</label>
												<input name="CVC" type="text" placeholder="your CVC" />
											</div>
										</div>
									</div>

									<div class="billing-address">
										<div class="heading">Add Billing Address</div>
										<div class="inputs-container">
											<div>
												<label for="address">Address:</label>
												<input name="address" type="text" placeholder="your address" />
											</div>
											<div>
												<label for="country">Country:</label>
												<input name="country" type="text" placeholder="your country" />
											</div>
											<div>
												<label for="city">City:</label>
												<input name="city" type="text" placeholder="your city" />
											</div>
											<div>
												<label for="state">State:</label>
												<input name="state" type="text" placeholder="your state" />
											</div>
											<div>
												<label for="zip-code">Zip-Code:</label>
												<input name="zip-code" type="text" placeholder="your zip-code" />
											</div>
										</div>
									</div>
								</div>
								<div class="checkout-info-add-btn">
									<button class="close-btn btn btn-secondary">Close</button>
									<button class="btn btn-primary">Save</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="checkout-row" id="review-and-shipping">
					<div class="first-box">
						<span>3</span>
						<p>review items & shipping</p>
					</div>
					<div class="box">
						<div class="heading">
							<h3>Lorem ipsum dolor sit amet.</h3>
							<p>
								Lorem ipsum dolor sit, amet consectetur adipisicing elit. Doloremque molestias ex quasi consequatur excepturi ullam cum aliquid
								voluptates iure ut itaque perferendis, maiores laudantium eaque voluptate at rem iusto. Ipsa voluptatem minima ipsam dolore distinctio
								illo assumenda, dignissimos vitae tempora.
							</p>
						</div>

						<div class="product">
							<div class="image">
								<img src="https://source.unsplash.com/collection/random" alt="" height="100" width="100" />
							</div>
							<div class="details">
								<h4>Product Name</h4>
								<p>Details: Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ullam, earum?</p>
								<p>Price: $100</p>
								<div class="product-quantity">
									<p>Quantity:</p>
									<button class="btn btn-secondary">+</button>
									<span>1</span>
									<button class="btn btn-secondary">-</button>
								</div>
								<div class="shipping-cost">
									<p>shipping cost:</p>
									<p>$100</p>
									<select name="" id="">
										<option value="">Us</option>
										<option value="">Canada</option>
										<option value="">Germany</option>
									</select>
									<button class="btn btn-secondary">calculate</button>
								</div>
							</div>
						</div>

						<div class="product">
							<div class="image">
								<img src="https://source.unsplash.com/collection/random" alt="" height="100" width="100" />
							</div>
							<div class="details">
								<h4>Product Name</h4>
								<p>Details: Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ullam, earum?</p>
								<p>Price: $100</p>
								<div class="product-quantity">
									<p>Quantity:</p>
									<button class="btn btn-secondary">+</button>
									<span>1</span>
									<button class="btn btn-secondary">-</button>
								</div>
								<div class="shipping-cost">
									<p>shipping cost:</p>
									<p>$100</p>
									<select name="" id="">
										<option value="">Us</option>
										<option value="">Canada</option>
										<option value="">Germany</option>
									</select>
									<button class="btn btn-secondary">calculate</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="checkout-right">
				<div class="box">
					<div class="summary">
						<p>Order summary</p>
						<div class="details">
							<div>
								<p>Items(2):</p>
								<p>$199.00</p>
							</div>
							<div>
								<p>shipping & handling:</p>
								<p>$00.00</p>
							</div>
							<div>
								<p>Total before tax:</p>
								<p>$199.00</p>
							</div>
							<div>
								<p>tax:</p>
								<p>$10.00</p>
							</div>
							<div>
								<p>order total:</p>
								<p>$209.00</p>
							</div>
						</div>
					</div>
					<div class="header">
						<button class="btn btn-warning">Place your Order</button>
						<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quasi commodi neque</p>
					</div>
				</div>
			</div>
		</section>

		<script>
			const checkoutBtns = document.querySelectorAll('.dropdown-btn');
			const modalBox = document.querySelectorAll('.dropdown-box');
			const closeBtns = document.querySelectorAll('.close-btn');

			checkoutBtns.forEach((btn) => {
				btn.addEventListener('click', (e) => {
					e.target.nextElementSibling.classList.add('active');
				});
			});
			modalBox.forEach((modal) => {
				modal.addEventListener('click', (e) => {
					if (e.target.classList.contains('active') && e.target.classList.contains('dropdown-box')) {
						e.target.classList.remove('active');
					}
				});
			});

			closeBtns.forEach((btn) => {
				btn.addEventListener('click', (e) => {
					modalBox.forEach((modal) => {
						modal.classList.remove('active');
					});
				});
			});
		</script>