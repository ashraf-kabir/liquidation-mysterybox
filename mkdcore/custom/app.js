try {
  const categoryCollapseImg = document.querySelector('.category-collapse-img')
  const categoryList = document.querySelector('.categoryList')
  const categoryExpandImg = document.querySelector('.category-expand-img')

  categoryCollapseImg.addEventListener('click', () => {
    categoryList.style.transform = 'translateX(-100rem)'
    setTimeout(() => {
      categoryList.style.display = 'none'
    }, 300)
    categoryExpandImg.style.zIndex = '100'
  })

  categoryExpandImg.addEventListener('click', (evt) => {
    categoryList.style.display = 'block'
    categoryList.style.transform = 'translateX(0)'
    categoryExpandImg.style.zIndex = '-100'
  })
} catch {}

// Additional Business Address Line

try {
  const addAdditionalBusinessAddress = document.querySelector(
    '.addAdditionalBusinessAddress'
  )
  const addAdditionalBusinessAddressForm = document.querySelector(
    '.addAdditionalBusinessAddressForm'
  )
  addAdditionalBusinessAddress.addEventListener('click', () => {
    addAdditionalBusinessAddressForm.style.display = 'block'
    addAdditionalBusinessAddress.style.display = 'none'
  })
} catch {}

// Additional Account
try {
  const addAnotherDocument = document.querySelector('#add-another-document')
  const anotherDocument = document.querySelector('.another-document')

  addAnotherDocument.addEventListener('click', () => {
    anotherDocument.style.display = 'block'
    addAnotherDocument.style.display = 'none'
  })
} catch {}

// File uploads
// Step 1 Registration
try {
  const fileNameTax = document.querySelector('.upload-taxID-photo-fileName')
  const mockUploadDocument = document.querySelector('.mock-upload-document-s1')
  const actualUploadDocumentS1 = document.querySelector('.upload-document-s1')
  mockUploadDocument.addEventListener('click', () => {
    actualUploadDocumentS1.style.display = 'block'
    actualUploadDocumentS1.click()
  })
  actualUploadDocumentS1.addEventListener('change', () => {
    if (actualUploadDocumentS1.files.length !== 0) {
      fileNameTax.innerText = actualUploadDocumentS1.files[0].name
    }
  })
} catch {}

// Step 2 of registration
try {
  const fileNameDocument = document.querySelector(
    '.upload-document-photo-fileName'
  )
  const mockUploadDocument = document.querySelector('.mock-upload-document')
  const actualUploadDocument = document.querySelector('.upload-document')
  mockUploadDocument.addEventListener('click', () => {
    actualUploadDocument.style.display = 'block'
    actualUploadDocument.click()
  })
  actualUploadDocument.addEventListener('change', () => {
    if (actualUploadDocument.files.length !== 0) {
      fileNameDocument.innerText = actualUploadDocument.files[0].name
    }
  })
} catch {}

// Step 3
try {
  const fileNameDocument2 = document.querySelectorAll(
    '.upload-document2-photo-fileName'
  )
  const mockUpload = document.querySelectorAll('.mock-upload')
  const actualUpload = document.querySelectorAll('.upload')
  for (let item in mockUpload) {
    mockUpload[item].addEventListener('click', () => {
      actualUpload[item].style.display = 'block'
      actualUpload[item].click()
    })
    actualUpload[item].addEventListener('change', () => {
      if (actualUpload[item].files.length !== 0) {
        fileNameDocument2[item].innerText = actualUpload[item].files[0].name
      }
    })
  }
} catch {}

// Upload ID Photo
try {
  const fileName = document.querySelector('.upload-id-photo-fileName')
  const mockUploadIDPhoto = document.querySelector('.mock-upload-id-photo')
  const actualUploadIDPhoto = document.querySelector('.upload-id-photo')
  mockUploadIDPhoto.addEventListener('click', async () => {
    actualUploadIDPhoto.style.display = 'block'
    actualUploadIDPhoto.click()
  })
  actualUploadIDPhoto.addEventListener('change', () => {
    if (actualUploadIDPhoto.files.length !== 0) {
      fileName.innerText = actualUploadIDPhoto.files[0].name
    }
  })
} catch {}

// Upload Certificate Photo
try {
  const fileNameCertificate = document.querySelectorAll(
    '.upload-certificate-photo-fileName'
  )
  const mockUploadCertificatePhoto = document.querySelectorAll(
    '.mock-upload-certificate-photo'
  )
  const actualUploadCertificatePhoto = document.querySelectorAll(
    '.upload-certificate-photo'
  )
  for (let item in mockUploadCertificatePhoto) {
    mockUploadCertificatePhoto[item].addEventListener('click', () => {
      actualUploadCertificatePhoto[item].style.display = 'block'
      actualUploadCertificatePhoto[item].click()
    })
    actualUploadCertificatePhoto[item].addEventListener('change', () => {
      if (actualUploadCertificatePhoto[item].files.length !== 0) {
        fileNameCertificate[item].innerText =
          actualUploadCertificatePhoto[item].files[0].name
      }
    })
  }
} catch {}

// Additional Material
try {
  const addAnotherMaterial = document.querySelector('.addAnotherMaterial')
  const anotherMaterial = document.querySelector('.anotherMaterial')

  addAnotherMaterial.addEventListener('click', () => {
    anotherMaterial.style.display = 'flex'
    addAnotherMaterial.style.display = 'none'
  })
} catch {}
// Additional Location Address
try {
  const addAdditionalLocationAddress = document.querySelector(
    '.addAdditionalLocationAddress'
  )
  const additionalBusinessAddress = document.querySelector(
    '.additionalBusinessAddress'
  )

  addAdditionalLocationAddress.addEventListener('click', () => {
    additionalBusinessAddress.style.display = 'block'
    addAdditionalLocationAddress.style.display = 'none'
  })
} catch {}

// Additional Location Address Profile
try {
  const addAdditionalLocationAddressProfile = document.querySelector(
    '.addAdditionalLocationAddressProfile'
  )
  const additionalLocationAddressProfile = document.querySelector(
    '.additionalLocationAddressProfile'
  )

  addAdditionalLocationAddressProfile.addEventListener('click', () => {
    additionalLocationAddressProfile.style.display = 'block'
    addAdditionalLocationAddressProfile.style.display = 'none'
  })
} catch {}

// Additional Business Address for Supplier Profile
try {
  const addAdditionalBusinessAddressSupplierProfile = document.querySelector(
    '.addAdditionalBusinessAddressSupplierProfile'
  )
  const additionalBusinessAddressSupplierProfile = document.querySelector(
    '.additionalBusinessAddressSupplierProfile'
  )

  addAdditionalBusinessAddressSupplierProfile.addEventListener('click', () => {
    additionalBusinessAddressSupplierProfile.style.display = 'block'
    addAdditionalBusinessAddressSupplierProfile.style.display = 'none'
  })
} catch {}

// Additional Business Address Line 2 for Corporate Portal

try {
  const addBusinessAddressPortalProvider = document.querySelector(
    '.addBusinessAddressPortalProvider'
  )
  const additionalBusinessAddressPortalProvider = document.querySelector(
    '.additionalBusinessAddressPortalProvider'
  )

  addBusinessAddressPortalProvider.addEventListener('click', () => {
    additionalBusinessAddressPortalProvider.style.display = 'block'
    addBusinessAddressPortalProvider.style.display = 'none'
  })
} catch {}
// Additional Skill

try {
  const addAnotherSkill = document.querySelector('.addAnotherSkill')
  const additionalSkill = document.querySelector('.additionalSkill')

  addAnotherSkill.addEventListener('click', () => {
    additionalSkill.style.display = 'block'
    addAnotherSkill.style.display = 'none'
  })
} catch {}

// Tags in Portal Provider

// Mocking Tag Feature
try {
  const sampleEquipmentArray = ['tow truck', 'crane', 'item1', 'item2', 'item3']
  const tagList = document.querySelector('.tag-list')
  const equipment = document.querySelector('#equipment-tags')
  let tagsArray = []
  equipment.addEventListener('keypress', (evt) => {
    let filteredItem = []
    if (document.querySelectorAll('.list-group-item').length !== 0) {
      tagList.innerHTML = ''
    }
    filteredItem = sampleEquipmentArray.filter((item) =>
      item.includes(evt.target.value.toLowerCase())
    )
    if (filteredItem.length !== 0) {
      tagList.style.display = 'block'
      for (let i of filteredItem) {
        let li = document.createElement('li')
        li.className = 'list-group-item'
        li.innerText = i
        li.style.cursor = 'pointer'
        tagList.appendChild(li)
      }
    }

    const tagItem = document.querySelectorAll('.tag-item')
    const tagBox = document.querySelector('.tagBox')
    for (let item of document.querySelectorAll('.list-group-item')) {
      item.addEventListener('click', (evt) => {
        if (tagItem.length !== 0) {
          if (!tagsArray.includes(evt.target.innerText.toLowerCase())) {
            tagBox.innerHTML += `<div class="tag-item mr-2">
            <h1 class="secondaryHeading3 mb-0 mr-3">${evt.target.innerText}</h1>
            <img src="../src/assets/provider/cross.svg" alt="" />
            </div>`
            tagsArray.push(evt.target.innerText.toLowerCase())
          }
        } else {
          tagBox.innerHTML = `<div class="tag-item mr-2">
          <h1 class="secondaryHeading3 mb-0 mr-3">${evt.target.innerText}</h1>
          <img src="../src/assets/provider/cross.svg" alt="" />
          </div>`
          tagsArray.push(evt.target.innerText.toLowerCase())
        }
        tagList.style.display = 'none'
      })
    }
  })
  document.addEventListener('click', (evt) => {
    for (let item of document.querySelectorAll('.tag-item')) {
      item.children[1].addEventListener('click', () => {
        item.style.display = 'none'
        tagsArray.splice(
          tagsArray.indexOf(item.firstElementChild.innerText.toLowerCase()),
          1
        )
      })
    }
    if (!tagList.contains(evt.target) && !equipment.contains(evt.target)) {
      tagList.style.display = 'none'
    } else if (document.querySelector('.tagBox').contains(evt.target)) {
    }
  })
} catch {}

// Profile Image
try {
  const addProfileImage = document.querySelector('.addProfileImage')
  const profilePictureInput = document.querySelector('.profile-picture')
  const ppValidity = document.querySelector('.ppValidity')
  const image = document.querySelector('.profile-img')

  addProfileImage.addEventListener('click', (evt) => {
    profilePictureInput.click()
    profilePictureInput.addEventListener('change', () => {
      if (profilePictureInput.files.length !== 0) {
        let pp = profilePictureInput.files[0].name.toLowerCase()
        if (
          pp.endsWith('.jpg') ||
          pp.endsWith('.jpeg') ||
          pp.endsWith('.png') ||
          pp.endsWith('.svg')
        ) {
          ppValidity.innerText = ''
        } else {
          ppValidity.innerText = 'Please upload valid file for images.'
        }
      }
    })
  })
} catch {}
