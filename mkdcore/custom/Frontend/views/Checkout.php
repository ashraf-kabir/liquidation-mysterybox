<style>
    .checkout-section {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
}

.checkout-section .checkout-left {
  width: 70%;
  margin-right: 20px;
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

.checkout-section .checkout-left .checkout-row .first-box {
  font-weight: bold;
  margin-right: 40px;
}

.checkout-section .checkout-left .checkout-row .first-box span {
  text-transform: capitalize;
}

.checkout-section .checkout-left .checkout-row .first-box span:nth-child(1) {
  margin-right: 20px;
}

.checkout-section .checkout-left .checkout-row .second-box {
  -webkit-box-flex: 1;
      -ms-flex: 1;
          flex: 1;
}

.checkout-section .checkout-left .checkout-row .third-box {
  position: relative;
}

.checkout-section .checkout-left .checkout-row .third-box button {
  text-transform: capitalize;
  padding: 0px 10px;
}

.checkout-section .checkout-left .checkout-row .third-box .dropdown-box {
  position: absolute;
  border: 1px solid grey;
  padding: 1rem;
  width: 250px;
  top: 1.5rem;
  left: 0;
  background-color: white;
  z-index: 10;
  border-radius: 5px;
  display: none;
}

.checkout-section .checkout-left .checkout-row .third-box .dropdown-box.active {
  display: block;
}

.checkout-section .checkout-left .checkout-row .third-box .dropdown-box .checkout-address {
  padding: 1rem 0;
  text-align: center;
  cursor: pointer;
}

.checkout-section .checkout-left .checkout-row .third-box .dropdown-box .checkout-address:not(:last-child) {
  border-bottom: 1px solid grey;
}

.checkout-section .checkout-left .checkout-row .third-box .dropdown-box .checkout-address:hover {
  background-color: #eee;
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
  height: 200vh;
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

.checkout-section .checkout-right .box .header button {
  -webkit-appearance: none;
     -moz-appearance: none;
          appearance: none;
  outline: none;
  border: 1px solid grey;
  padding: 10px;
  border-radius: 5px;
  width: 100%;
  background-color: #d1b201;
  text-transform: capitalize;
}

.checkout-section .checkout-right .box .header p {
  padding: 5px 0 10px 0;
  font-size: 12px;
  text-align: center;
  border-bottom: 1px solid rgba(128, 128, 128, 0.5);
}

.checkout-section .checkout-right .box .summary > p {
  font-size: 25px;
  font-weight: bold;
  margin: 10px 0;
  text-transform: capitalize;
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
  border-bottom: none
  }

.checkout-row#review-and-shipping .first-box {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  margin-right: 0;
}

.checkout-row#review-and-shipping .first-box > div {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
      -ms-flex-direction: column;
          flex-direction: column;
}

.checkout-row#review-and-shipping .first-box > div > p {
  text-transform: capitalize;
}

.checkout-row#review-and-shipping .first-box > div .box {
  margin-top: 20px;
  border: 1px solid rgba(128, 128, 128, 0.5);
  padding: 10px 20px;
  border-radius: 5px;
}

.checkout-row#review-and-shipping .first-box > div .box .heading {
  text-align: center;
  margin-bottom: 20px;
}

.checkout-row#review-and-shipping .first-box > div .box .heading h3 {
  font-size: 22px;
  text-transform: capitalize;
  margin-bottom: 10px;
}

.checkout-row#review-and-shipping .first-box > div .box .heading p {
  font-size: 14px;
}

.checkout-row#review-and-shipping .first-box > div .box .product {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
      -ms-flex-align: center;
          align-items: center;
  margin: 20px 0;
}

.checkout-row#review-and-shipping .first-box > div .box .product .image {
  margin-right: 20px;
}

.checkout-row#review-and-shipping .first-box > div .box .product .details h4 {
  font-size: 20px;
}

.checkout-row#review-and-shipping .first-box > div .box .product .details .product-quantity {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
}

.checkout-row#review-and-shipping .first-box > div .box .product .details .product-quantity button {
  padding: 0 5px;
  margin: 0 10px;
}
</style>

<section class="checkout-section">
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
						<button class="dropdown-btn">change</button>
						<div class="dropdown-box">
							<div class="checkout-address">
								<p>Lorem ipsum dolor sit.</p>
								<p>Lorem ipsum dolor sit.</p>
								<p>Lorem ipsum dolor sit.</p>
							</div>
							<div class="checkout-address">
								<p>Lorem ipsum dolor sit.</p>
								<p>Lorem ipsum dolor sit.</p>
								<p>Lorem ipsum dolor sit.</p>
							</div>
							<div class="checkout-address">
								<p>Lorem ipsum dolor sit.</p>
								<p>Lorem ipsum dolor sit.</p>
								<p>Lorem ipsum dolor sit.</p>
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
						<button class="dropdown-btn">change</button>
						<div class="dropdown-box">
							<div class="checkout-address">
								<p>Lorem ipsum dolor sit.</p>
								<p>Lorem ipsum dolor sit.</p>
								<p>Lorem ipsum dolor sit.</p>
							</div>
							<div class="checkout-address">
								<p>Lorem ipsum dolor sit.</p>
								<p>Lorem ipsum dolor sit.</p>
								<p>Lorem ipsum dolor sit.</p>
							</div>
							<div class="checkout-address">
								<p>Lorem ipsum dolor sit.</p>
								<p>Lorem ipsum dolor sit.</p>
								<p>Lorem ipsum dolor sit.</p>
							</div>
						</div>
					</div>
				</div>
				<div class="checkout-row" id="review-and-shipping">
					<div class="first-box">
						<span>3</span>
						<div>
							<p>review items & shipping</p>
							<div class="box">
								<div class="heading">
									<h3>Lorem ipsum dolor sit amet.</h3>
									<p>
										Lorem ipsum dolor sit, amet consectetur adipisicing elit. Doloremque molestias ex quasi consequatur excepturi ullam cum aliquid
										voluptates iure ut itaque perferendis, maiores laudantium eaque voluptate at rem iusto. Ipsa voluptatem minima ipsam dolore
										distinctio illo assumenda, dignissimos vitae tempora.
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
											<button>+</button>
											<span>1</span>
											<button>-</button>
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
											<button>+</button>
											<span>1</span>
											<button>-</button>
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
											<button>+</button>
											<span>1</span>
											<button>-</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="checkout-right">
				<div class="box">
					<div class="header">
						<button>Place your Order</button>
						<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quasi commodi neque</p>
					</div>
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
				</div>
			</div>
		</section>

		<script>
			const checkoutBtns = document.querySelectorAll('.dropdown-btn');

			checkoutBtns.forEach((btn) => {
				btn.addEventListener('click', (e) => {
					e.target.nextElementSibling.classList.toggle('active');
				});
			});

			document.body.addEventListener('click', (e) => {
				if (!e.target.classList.contains('dropdown-btn')) {
					checkoutBtns.forEach((btn) => {
						btn.nextElementSibling.classList.remove('active');
					});
				}
			});
		</script>