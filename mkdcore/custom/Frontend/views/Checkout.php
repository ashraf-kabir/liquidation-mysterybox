<section>
      <div class="container-fluid px-5 py-5">
        <div class="row justify-content-around">
          <div class="col-xl-8 col-lg-9 col-md-8 col-12 my-3">
            <div class="bg-white w-100 p-2 p-md-4">
              <h5>Sign in to Checkout</h5>
              <button class="btn btn-secondary bg-dark my-3">SIGN IN</button>

              <p class="text-muted my-3">or checkout as guest</p>
            </div>
            <div class="bg-white w-100 p-2 p-md-4">
              <h5>Personal Information</h5>
              <hr />
              <form action="" id="personal-information">
                <div class="form-row justify-content-between">
                  <div class="col-xl-5 col-lg-5 col-md-5 col-12 my-3">
                    <label for="name">Full Name</label>
                    <input
                      type="text"
                      name="name"
                      id="name"
                      placeholder="Enter your full name"
                      class="form-control"
                    />
                  </div>
                  <div class="col-xl-5 col-lg-5 col-md-5 col-12 my-3">
                    <label for="email">Email Address</label>
                    <input
                      type="text"
                      name="email"
                      id="email"
                      placeholder="abc@example.com"
                      class="form-control"
                    />
                  </div>
                  <div class="col-xl-5 col-lg-5 col-md-5 col-12 my-3">
                    <label for="number">Phone Number</label>
                    <input
                      type="number"
                      name="number"
                      id="number"
                      placeholder="+123-456-789"
                      class="form-control"
                    />
                  </div>
                  <div class="col-xl-5 col-lg-5 col-md-5 col-12 my-3">
                    <label for="city">City</label>
                    <input
                      type="text"
                      name="city"
                      id="city"
                      placeholder="Enter your city"
                      class="form-control"
                    />
                  </div>
                  <div class="col-xl-5 col-lg-5 col-md-5 col-12 my-3">
                    <label for="state">State</label>
                    <input
                      type="text"
                      name="state"
                      id="state"
                      placeholder="Enter your State"
                      class="form-control"
                    />
                  </div>
                  <div class="col-xl-5 col-lg-5 col-md-5 col-12 my-3">
                    <label for="country">country</label>
                    <input
                      type="text"
                      name="country"
                      id="country"
                      placeholder="Enter your Country"
                      class="form-control"
                    />
                  </div>
                  <div class="col-12 my-3">
                    <label for="address-1">Address 1</label>
                    <input
                      type="text"
                      name="address-1"
                      id="address-1"
                      placeholder="Address here..."
                      class="form-control"
                    />
                  </div>
                  <div class="col-12 my-3">
                    <label for="address-2">Address 2 (Optional)</label>
                    <input
                      type="text"
                      name="address-2"
                      id="address-2"
                      placeholder="Address here..."
                      class="form-control"
                    />
                  </div>
                </div>
              </form>
            </div>

            <div class="bg-white p-2 p-md-4 my-4">
              <h5>Choose Payment Method</h5>
              <div
                class="col-xl-6 col-lg-6 col-md-8 col-sm-8 col-12 d-flex justify-content-between"
              >
                <label for="cash">
                  <input
                    type="radio"
                    name="payment"
                    id="cash"
                    value="cash"
                    class="mr-1"
                  />
                  Cash
                </label>
                <label for="cash">
                  <input
                    type="radio"
                    name="payment"
                    id="card"
                    value="card"
                    class="mr-1"
                  />
                  Credit Card/Master
                </label>
                <label for="ach">
                  <input
                    type="radio"
                    name="payment"
                    id="ach"
                    value="ach"
                    class="mr-1"
                  />
                  ACH
                </label>
                <label for="bank">
                  <input
                    type="radio"
                    name="payment"
                    id="bank"
                    value="bank"
                    class="mr-1"
                  />
                  Bank
                </label>
              </div>
            </div>

            <div class="bg-white p-2 p-md-4 my-4">
              <h5>Payment Method</h5>
              <hr />
              <form action="">
              <div class="form-row">
                <label for="account-title">Account Title</label>
                <input
                  type="text"
                  name="account-title"
                  id="account-title"
                  placeholder="Enter Account Title"
                  class="form-control"
                />
                <label for="account-number">Account Number</label>
                <input
                  type="number"
                  name="account-number"
                  id="account-number"
                  placeholder="Enter Account Number"
                  class="form-control"
                />
                <label for="bank-rounting">Bank Routing Number</label>
                <input
                  type="text"
                  name="bank-rounting"
                  id="bank-rounting"
                  placeholder="Enter Account Title"
                  class="form-control"
                />
                <select name="account-type" id="account-type" class="form-control">
                    <option value="individual">Individual</option>
                    <option value="individual">Individual</option>
                    <option value="individual">Individual</option>
                    <option value="individual">Individual</option>
                </select>
            </form>
              </div>
            </div>

            <div class="bg-white w-100 p-2 p-md-4">
              <h5>Your Order</h5>
              <hr />

              <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-6 my-2">
                  <h5>Subtotal</h5>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-6 my-2">
                  <h5>$40.75</h5>
                </div>
              </div>
              <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-6 my-2">
                  <h5>Ship to Home</h5>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-6 my-2">
                  <h5>$40.75</h5>
                </div>
              </div>
              <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-6 my-2">
                  <h5>Tax</h5>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-6 my-2">
                  <h5>$40.75</h5>
                </div>
              </div>
              <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-6 my-2">
                  <h4>Total</h4>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-6 my-2">
                  <h5>$40.75</h5>
                </div>
              </div>

              <div class="row mt-4 justify-content-between px-4">
                <label for="terms">
                  <input type="checkbox" name="terms" id="terms" class="mr-2" />
                  I’ve read and accept the terms & conditions
                </label>
                <button class="btn btn-success">Place Order</button>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-lg-3 col-md-4 col-12 my-3">
            <div class="bg-white">
              <div class="cart__address p-4">
                <h4 class="my-4"><i class="fas fa-truck"></i> Shipping</h4>
                <h5 class="my-3">A-Block</h5>
                <p class="w-75 my-2">
                  Street 123, abc Road, New York city123-555-789
                </p>
              </div>
              <hr />
              <div class="cart__summary px-4 py-4">
                <h5><i class="fas fa-cart-plus"></i> Order Summary</h5>
                <div class="d-flex justify-content-between my-4">
                  <h6>Subtotal</h6>
                  <h6>$<span class="cart-subtotal">00.00</span></h6>
                </div>
                <div class="d-flex justify-content-between my-4">
                  <h6>Shipping</h6>
                  <h6>$<span class="cart-shipping">00.00</span></h6>
                </div>
                <div class="d-flex justify-content-between my-4">
                  <h6>Tax</h6>
                  <h6>$<span class="cart-tax">00.00</span></h6>
                </div>
                <div class="d-flex justify-content-between my-4">
                  <strong>Total</strong>
                  <strong>$<span class="cart-total">00.00</span></strong>
                </div>
  
                <a href="/checkout.html" class="btn btn-success w-100"
                  >Checkout Out</a
                >
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>