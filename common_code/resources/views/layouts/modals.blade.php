<!-- MODALS ======================================================== -->
<form novalidate action="" method="POST" id="form-status-change">
@csrf
<div class="modal fade hide" id="vacancyStatusModal" tabindex="-1" aria-labelledby="vacancyStatusModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="vacancyStatusModalTitle">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="vacancyId" id="status-vid"/>
        <input type="hidden" name="applicantId" id="status-aid"/>
        <input type="hidden" name="status" id="status-type"/>
        <input type="hidden" name="active_tab" id="active_tab"/>
        <input type="hidden" name="comments_temp" id="comments_temp"/>
        <div class="mb-3" style="display:none" id="notify_div">
            <input type="checkbox" name="is_to_notify_applicant" id="is_to_notify_applicant" checked/> 
            <label for="is_to_notify_applicant" class="form-label mx-2">Notify Applicant</label>
        </div>
        <div class="mb-3">
          <label for="application_status" class="form-label">Status To <strong style="color:red">*</strong></label>
          <select class="form-control" required name="application_status" id="application_status" onchange="changeApplicationStatusMsg()">
          </select>
        </div>
        <div class="mb-3">
          <label for="status-notes" class="form-label">Your Comments/Meeting Link <strong style="color:red">*</strong></label>
          <textarea style="height:250px;" name="comments" required class="form-control" id="comments"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger p-2 px-3"  data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-info p-2 px-3 text-white">Confirm</button>
      </div>
    </div>
  </div>
</div>
</form>

<form novalidate action="" method="POST" id="introduce-to-company">
@csrf
<div class="modal fade hide" id="introduceToCompanyModal" tabindex="-1" aria-labelledby="introduceToCompanyModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="introduceToCompanyModalTitle">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="companyId" id="status-cid"/>
        <p id="status-message"></p>
        <div class="mb-3">
          <label for="status-notes" class="form-label">Your Comments</label>
          <textarea name="notes" class="form-control" id="notes" placeholder="(Optional)"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger p-2 px-3"  data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-info p-2 px-3 text-white">Send</button>
      </div>
    </div>
  </div>
</div>
</form>

<form novalidate action="" method="POST" id="inquiry-status-change">
@csrf
<div class="modal fade hide" id="inquiryStatusModal" tabindex="-1" aria-labelledby="inquiryStatusModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="inquiryStatusModalTitle">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="inquiryApplicantId" id="inquiry-aid"/>
        <input type="hidden" name="inquiryStatus" id="inquiry-status-type"/>
        <input type="hidden" name="active_tab_inquiry" id="active_tab_inquiry"/>

        <div class="mb-3">
          <label for="status-notes" class="form-label">Your Comments</label>
          <textarea name="inquiryComments" class="form-control" id="inquiry-comments" placeholder="(Optional)"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger p-2 px-3"  data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-info p-2 px-3 text-white">Confirm</button>
      </div>
    </div>
  </div>
</div>
</form>
@yield("modal")

<!-- SHARE MODAl ===================================================== -->
<div class="modal fade hide" id="shareModal" tabindex="-1" aria-labelledby="shareModal" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Share with others</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="d-flex justify-content-center">
              <a href="https://www.facebook.com/Placementslk-107302274899807/" id="link-facebook" target="_blank" class="w-100 mx-1" title="Facebook">
                  <div class="p-2 text-center bg-light rounded">
                      <span class="m-0 font-200 text-theme"><i class="fab fa-facebook"></i> </span>
                      <span class="d-none d-xl-block font-80 text-dark">Facebook</span>
                  </div>
              </a>

              <a href="https://instagram.com/placements.lk" id="link-whatsapp" data-action="share/whatsapp/share" target="_blank" class="w-100 mx-1" title="WhatsApp">
                  <div class="p-2 text-center bg-light rounded">
                      <span class="m-0 font-200 text-theme"><i class="fab fa-whatsapp"></i> </span>
                      <span class="d-none d-xl-block font-80 text-dark">WhatsApp</span>
                  </div>
              </a>

              <a href="https://www.linkedin.com/company/placements-lk" id="link-linkedin" target="_blank" class="w-100 mx-1" title="LinkedIn">
                  <div class="p-2 text-center bg-light rounded">
                      <span class="m-0 font-200 text-theme"><i class="fab fa-linkedin"></i> </span>
                      <span class="d-none d-xl-block font-80 text-dark">LinkedIn</span>
                  </div>
              </a>
              
              <a href="https://twitter.com/PlacementsL?t=bQpr8jgYam6fWJTqPHoW9g&s=08" id="link-twitter" target="_blank" class="w-100 mx-1" title="Twitter">
                  <div class="p-2 text-center bg-light rounded">
                      <span class="m-0 font-200 text-theme"><i class="fab fa-twitter"></i> </span>
                      <span class="d-none d-xl-block font-80 text-dark">Twitter</span>
                  </div>
              </a>
          </div>
          <div class="d-flex justify-content-center mt-3">
              <a style="cursor:pointer" onclick="copyText('link-copy')" target="_blank" class="w-100 mx-1" title="Copy Link">
                <div class="p-2 text-center bg-light rounded w-100">
                    <span class="m-0 font-200 text-theme"><i class="fas fa-link"></i> </span>
                    <span class="d-none d-xl-block font-80 text-dark">Copy Link</span>
                    <input type="text" value="Hello World" id="link-copy" class="d-none" />
                </div>
              </a>
              <a href="https://instagram.com/placements.lk" id="link-email" target="_blank" class="w-100 mx-1" title="Email">
                  <div class="p-2 text-center bg-light rounded">
                      <span class="m-0 font-200 text-theme"><i class="fas fa-envelope"></i> </span>
                      <span class="d-none d-xl-block font-80 text-dark">Email</span>
                  </div>
              </a>
              <a href="https://instagram.com/placements.lk" id="link-viber" target="_blank" class="w-100 mx-1" title="Viber">
                  <div class="p-2 text-center bg-light rounded">
                      <span class="m-0 font-200 text-theme"><i class="fab fa-viber"></i> </span>
                      <span class="d-none d-xl-block font-80 text-dark">Viber</span>
                  </div>
              </a>
              <a href="https://instagram.com/placements.lk" id="link-telegram" target="_blank" class="w-100 mx-1" title="Telegram">
                  <div class="p-2 text-center bg-light rounded">
                      <span class="m-0 font-200 text-theme"><i class="fab fa-telegram"></i> </span>
                      <span class="d-none d-xl-block font-80 text-dark">Telegram</span>
                  </div>
              </a>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn bg-light p-2 px-3"  data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- OBJECT BROWSER MODAl ===================================================== -->
<div class="modal fade hide" id="objectBrowserModal" tabindex="-1" aria-labelledby="objectBrowserModal" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content" style="margin-top:80px">
      <div class="modal-header">
        <h5 class="modal-title" id="">Select Item</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body bg-standard" style="max-height:65vh;overflow-y:auto">
          <div class="filter-cont">
              <div class="row">
                  <div class="col-12 d-flex search-box">
                      <input type="text" class="form-control" name="search" value="" id="search-objects" autocomplete="off" placeholder="Search items..">
                      <button type="button" class="btn btn-primary"><i class="fas fa-search font-100"></i></button>
                  </div>
              </div>
          </div>

          <div class="object-items"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn bg-light p-2 px-3"  data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Ad MODAL ===================================================== -->
<div class="modal fade hide" id="adModal" tabindex="-1" aria-labelledby="adModal" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content" style="margin-top:80px">
      <div class="modal-header">
        <h5 class="modal-title" id=""></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0">
          <a href="https://placements.lk/career-fairs/placementslk-te-38In8bB2Y">
            <img src="" class="img-fluid">
          </a>
      </div>
      <div class="modal-footer">
        <a href="https://placements.lk/career-fairs/placementslk-te-38In8bB2Y" class="btn bg-success p-2 px-3 text-white">Read more</a>
        <button type="button" class="btn bg-light p-2 px-3"  data-bs-dismiss="modal" onclick="hideAd()">Close</button>
      </div>
    </div>
  </div>
</div>