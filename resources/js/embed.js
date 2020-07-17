var webshop_url = process.env.MIX_WEBSHOP_EMBED_URL;
window.webshop = {
  "query": {},
  "products_view": 'webshop-product-grid-view',
  "webshop_url" : webshop_url,
  "current_url": webshop_url+'/products',
  "breadcrumbs" : []
};

function parseQuery(search) {
  const params = {};
  if(search.indexOf('?') > -1) {
    const hashes = search.slice(search.indexOf('?') + 1).split('&');

    hashes.map(hash => {
      const [key, val] = hash.split('=');
      params[key] = decodeURIComponent(val);
    });
  }

  return params;
}

function makeQueryString(queryObj) {
  return Object.keys(queryObj).map(key => key + '=' + queryObj[key]).join('&');
}

function queryBuilder(param) {
  const [key, val] = param;
  if(key === 'global_search' && val) {
    window.webshop.query = {};
  }
  
  let queryObj = window.webshop.query;
  let queryString = '';

  if(key === 'search' && (!val || !val.toString().length)) {
    delete queryObj['search'];
    delete queryObj['page'];
    queryString = makeQueryString(queryObj);
    sendRequest(window.webshop.current_url.split(/[?#]/)[0]+'?'+queryString, 'products');
  } else {
    if (!val || !val.toString().length) {
      delete queryObj[key];
    } else {
      queryObj[key] = val;
    }
    if(key === 'search' || key === 'category') {
      delete queryObj['page'];
    }
    
    queryString = makeQueryString(queryObj);
    if(queryObj['page'] || queryObj['search'] || queryObj['sort'] || queryObj['category'] || key === 'category') {
      sendRequest(window.webshop.current_url.split(/[?#]/)[0]+'?'+queryString, 'products');
    } else {
      sendRequest(webshop_url+'/products?'+queryString, 'products');
    }

  }
  window.webshop.query = queryObj;
}

function sendRequest(url, q, name) {
  if(!url) return;
  var xhr = new XMLHttpRequest();
  xhr.open('GET', url, true);
  if(q === 'products' || q === 'breadcrumbs') {
    document.getElementById("webshop-products").innerHTML = 'Loading...';
  } else if(q === 'categories') {
    document.getElementById("webshop-categories").innerHTML = 'Loading...';
  }
  xhr.send();

  xhr.onreadystatechange = function() {
    if (xhr.readyState != 4) return;

    if (xhr.status != 200) {
      if(q === 'products' || q === 'breadcrumbs' || q === 'top-bar') {
        document.getElementById("webshop-products").innerHTML = '';
      } else if(q === 'categories') {
        document.getElementById("webshop-categories").innerHTML = '';
      }
      console.log('error:', xhr.status + ': ' + xhr.statusText );
    } else {
      if(q === 'products' || q === 'breadcrumbs' || q === 'top-bar') {
        if(q === 'top-bar') {
          document.getElementById("webshop-top-bar").innerHTML = xhr.responseText;
          if(window.location.hash.includes('store/') || window.location.hash.includes('seller/') || window.location.hash.includes('product/')){
            sendRequest(webshop_url+'/'+window.location.hash.substring(1), 'products');
          } else {
            sendRequest(webshop_url+'/products', 'products');
          }
        } else {
          
          if(q === 'breadcrumbs') {
            let currentQueryObj = parseQuery(url);
            window.webshop.query = currentQueryObj;
            if(currentQueryObj['category']) {
              window.webshop.query['category'] = currentQueryObj['category'];
              if(document.querySelector('.webshop-reset-category')) document.querySelector('.webshop-reset-category').classList.remove('hidden');
            } else {
              delete window.webshop.query['category'];
              if(document.querySelector('.webshop-reset-category')) document.querySelector('.webshop-reset-category').classList.add('hidden');
            }
            let li = document.querySelectorAll('#webshop-content ul.categories li');
            for (let i = 0; i < li.length; i++) {
              li[i].classList.remove('selected');
            }
            // if (window.webshop.query['category'] && window.webshop.query['category'].length > 0) {
            //   document.getElementById('webshop-category-' + window.webshop.query['category']).classList.add('selected');
            // }
          }

          document.getElementById("webshop-products").innerHTML = xhr.responseText;

          let search = document.querySelector('#webshop-content .webshop-product-search');
          if(search && window.webshop.query.search) {
            document.querySelector('#webshop-content .webshop-product-search').value = window.webshop.query.search;
          }
          
          /********* Breadcrumbs handling *********/

          let hash = url.split('/').splice(4).join('/');
          if (url.includes('search=')) {
            window.location.hash = '';
            let term = window.webshop.query.search || window.webshop.query.global_search || '';
            breadcrumbs(url, 'Search: "'+term+'"');
          } else if (url.includes('/seller')) {
            window.location.hash = hash;
            let seller_name = document.querySelector('.seller-name a').innerText || '';
            breadcrumbs(url, seller_name);
          } else if (url.includes('/store')) {
            window.location.hash = hash;
            let store_name = document.querySelector('.store-name a').innerText || '';
            breadcrumbs(url, store_name);
          } else if (url.includes('/product/')) {
            window.location.hash = hash;
            let product_name = document.querySelector('.product-title').innerText || '';
            breadcrumbs(url, product_name);
          } else if (url.endsWith('/products')) {
            window.location.hash = '';
            window.webshop.breadcrumbs = [];
            breadcrumbs(url, 'Home');
          } else {
            window.location.hash = '';
            breadcrumbs(url, '');
          }
          
          let bread = document.querySelector('.breadcrumbs');
          let bread_html = '';

          if(bread) {
            if (url.includes('category=')) {
              let category_title = document.querySelector('.category-title').innerText || '';
              breadcrumbs(url, category_title);
              bread.classList.add('hidden');
            } else {
              bread.classList.remove('hidden');
            }
  
            if(window.webshop.breadcrumbs.length > 1) {
              let breadcrmbs = window.webshop.breadcrumbs;
              for(let b = 0; b < breadcrmbs.length; b++) {
                if(breadcrmbs[b].name.length) {
                  bread_html += '<span><a class="webshop-page-link" href="'+breadcrmbs[b].url+'">'+breadcrmbs[b].name+'</a></span>';
                  if(b < breadcrmbs.length - 1) {
                    bread_html += '<span> → </span>';
                  } 
                }
              }
  
              bread.innerHTML = bread_html;
            }
          }

          /********* End Breadcrumbs handling *********/


          if(window.webshop.breadcrumbs.length < 2) {
            document.querySelector('.webshop-go-back').classList.add('disabled');
          } else {
            document.querySelector('.webshop-go-back').classList.remove('disabled');
          }

          if (document.querySelector('.webshop-hero-image') && url.includes('category=')) {
            document.querySelector('.webshop-hero-image').classList.add('hidden');
          } else {
            if(document.querySelector('.webshop-hero-image')) {
              document.querySelector('.webshop-hero-image').classList.remove('hidden');
            }
          }

          let globalSearchInput = document.querySelector('#webshop-content .webshop-global-search');
          if(globalSearchInput && window.webshop.query.global_search) {
            globalSearchInput.value = window.webshop.query.global_search;
          }
          
          let sort = document.querySelector('#webshop-content .webshop-products-sort');
          if(sort && window.webshop.query.sort) {
            document.querySelector('#webshop-content .webshop-products-sort').value = window.webshop.query.sort;
          }
          let product_view_button = document.getElementById(window.webshop.products_view);
          if(product_view_button) product_view_button.click();
        }
      } else if(q === 'categories') {
        // document.getElementById("webshop-categories").innerHTML = xhr.responseText;
      }
    }
  }
}

// function resetCategory(){
//   let li = document.querySelectorAll('#webshop-content ul.categories li');
//   let ul = document.querySelectorAll('#webshop-content ul.categories ul');
//   for (let i = 0; i < li.length; i++) {
//     li[i].classList.remove('selected');
//   }
//   for (let j = 0; j < ul.length; j++) {
//     ul[j].classList.add('collapsed');
//   }
// }

function scrollToTop() {
  window.scrollTo({
    top: document.getElementById('webshop-content').getBoundingClientRect().top,
    behavior: 'smooth'
  });
}

function breadcrumbs(url, name) {
  window.webshop.current_url = url;

  for(let i = 1; i < window.webshop.breadcrumbs.length; i++) {
    if(window.webshop.breadcrumbs[i].url === url) {
      window.webshop.breadcrumbs.length = i;
    }
  }
  
  let obj = {
    'name': name,
    'url': url
  };
  
  if(!window.webshop.breadcrumbs[window.webshop.breadcrumbs.length - 1] ||
    (window.webshop.breadcrumbs[window.webshop.breadcrumbs.length - 1] && window.webshop.breadcrumbs[window.webshop.breadcrumbs.length - 1].url !== url)
  ) {
    window.webshop.breadcrumbs.push(obj); 
  }
}

function addViewportMeta() {
  let viewportMeta = document.createElement('meta');
  viewportMeta.setAttribute('name', 'viewport');
  viewportMeta.content = 'width=device-width, initial-scale=1';
  document.getElementsByTagName('head')[0].appendChild(viewportMeta);
}

let metaNodes = document.querySelectorAll('meta');
let isViewportSet = false;
if(metaNodes.length) {
  for (let i = 0; i < metaNodes.length; i++) {
    if (metaNodes[i].getAttribute('name') && metaNodes[i].getAttribute('name') === 'viewport') {
      isViewportSet = true;
    }
    if (i === metaNodes.length - 1 && !isViewportSet) {
      addViewportMeta();
    }
  }
} else {
  addViewportMeta();
}

sendRequest(webshop_url+'/top-bar', 'top-bar');
// sendRequest(webshop_url+'/categories', 'categories');

document.addEventListener('click',function(e){
  let searchInput = document.querySelector('#webshop-content .webshop-product-search');
  let globalSearchInput = document.querySelector('#webshop-content .webshop-global-search');
  if(e.target.classList.contains('webshop-page-link') || e.target.parentElement.classList.contains('webshop-page-link')) {
    e.preventDefault();
    e.stopPropagation();
    let pageUrl = '';
    if(e.target.getAttribute('data-embed_url') || e.target.parentElement.getAttribute('data-embed_url')) {
      pageUrl = e.target.getAttribute('data-embed_url') || e.target.parentElement.getAttribute('data-embed_url');
    } else {
      pageUrl = e.target.getAttribute('href') || e.target.parentElement.getAttribute('href');
    }
    
    if(pageUrl.length) {
      window.webshop.query = {};
      globalSearchInput.value = '';
      // resetCategory();
      // document.querySelector('.webshop-reset-category').classList.add('hidden');
      
      let selectedTopLevelCategory = e.target.getAttribute('data-category_uid');
      if(selectedTopLevelCategory) {
        window.webshop.query.category = selectedTopLevelCategory;
      }
      
      sendRequest(pageUrl, 'products');
      scrollToTop();
    }
  } else if(e.target.classList.contains('webshop-go-back')) {
    e.preventDefault();
    e.stopPropagation();
    if(window.webshop.breadcrumbs.length > 1) {
      let breadcrumb = window.webshop.breadcrumbs.pop();
      if(breadcrumb.url === window.webshop.current_url && window.webshop.breadcrumbs.length > 1) {
        breadcrumb.url = window.webshop.breadcrumbs.pop().url;
      } else {
        breadcrumb.url = window.webshop.breadcrumbs[0].url;
      }
      sendRequest(breadcrumb.url, 'breadcrumbs');
    }
  } else if(e.target.classList.contains('page-link')) {

    /*===== Pagination handler =====*/

    e.preventDefault();
    e.stopPropagation();
    let pageUrl = e.target.getAttribute('href');
    let pageQuery = parseQuery(pageUrl);
    if(pageQuery.page.length) {
      queryBuilder(['page', pageQuery.page]);
      scrollToTop();
    }
  } else if((e.key === 'Enter' && e.target === globalSearchInput) || e.target.classList.contains('webshop-global-search-button') || e.target.parentElement.classList.contains('webshop-global-search-button')) {

    /*===== Global Search handler =====*/
    
    queryBuilder(['global_search', globalSearchInput.value]);
  } else if((e.key === 'Enter' && e.target === searchInput) || e.target.classList.contains('webshop-search-button') || e.target.parentElement.classList.contains('webshop-search-button')) {

    /*===== Search handler =====*/
    
    queryBuilder(['search', searchInput.value]);
  } else if(e.target.classList.contains('webshop-products-sort')) {

    /*===== Sort handler =====*/
    
    let sort = e.target;
    sort.addEventListener('change', function (event) {
      queryBuilder(['sort', e.target.value]);
    });
  } else if(e.target.classList.contains('webshop-category-link')) {

    /*===== Categories handler =====*/

    // e.preventDefault();
    // e.stopPropagation();
    // let li = document.querySelectorAll('#webshop-content ul.categories li');
    // for (let i = 0; i < li.length; i++) {
    //   li[i].classList.remove('selected');
    // }
    // e.target.parentElement.classList.add('selected');
    // if(e.target.nextElementSibling) {
    //   e.target.nextElementSibling.classList.toggle('collapsed');
    // }
    // document.querySelector('a.webshop-reset-category').classList.remove('hidden');
    //
    // let uid = e.target.getAttribute('data-uid');
    // if(uid.length) queryBuilder(['category', uid]);
  } else if(e.target.classList.contains('webshop-reset-category')) {
    // e.preventDefault();
    // e.stopPropagation();
    // resetCategory();
    // e.target.classList.add('hidden');
    // queryBuilder(['category', '']);
  } else if(e.target.classList.contains('webshop-product-view') || e.target.closest('.webshop-product-view')) {

    /*===== Products view handler =====*/

    e.preventDefault();
    e.stopPropagation();

    let target_button = null;
    if(e.target.classList.contains('webshop-product-view')) {
      target_button = e.target;
    } else {
      target_button = e.target.closest('.webshop-product-view');
    }

    let view_buttons = document.querySelectorAll('.webshop-product-view');
    if(view_buttons.length) {
      Array.from(view_buttons).map(button => {
        button.classList.remove('active')
      });
    }

    target_button.classList.add('active');
    let products_class = target_button.getAttribute('data-viewclass');
    window.webshop.products_view = target_button.getAttribute('id');

    let products_container = document.querySelector('#webshop-products .products-listing');
    let products = document.querySelectorAll('#webshop-products .webshop-product-item');
    if(products.length) {
      Array.from(products).map(product => {
        product.className = 'webshop-product-item';
        product.classList.add(products_class);
      });
    }

    if(products_container) {
      let prefix = "container-";
      let classes = products_container.className.split(" ").filter(function(c) {
        return c.lastIndexOf(prefix, 0) !== 0;
      });
      products_container.className = classes.join(" ").trim();
      products_container.classList.add('container-'+products_class);
    }
  } else if(e.target.classList.contains('webshop-product-image')) {

    e.preventDefault();
    e.stopPropagation();

    let modal = document.getElementById("webshop-image-modal");
    let modalImg = document.getElementById("webshop-modal-content");

    if(modal) {
      modal.style.display = "block";
      modalImg.src = e.target.getAttribute('src');
    }

    let close = document.getElementsByClassName("webshop-modal-close")[0];
    if(close) {
      close.onclick = function() {
        modal.style.display = "none";
      }
    }
    
    
  } else if(e.target.classList.contains('webshop-product-thumb')) {
    e.preventDefault();
    e.stopPropagation();

    let product_img = document.getElementsByClassName("webshop-product-image")[0];
    product_img.src = e.target.getAttribute('src');
  }
});

/*===== Search handler =====*/
document.addEventListener('keydown',function(e){
  let searchInput = document.querySelector('#webshop-content .webshop-product-search');

  if(e.key === 'Enter' && e.target === searchInput) {
    queryBuilder(['search', e.target.value]);
  }
});
document.addEventListener('keydown',function(e){
  let globalSearchInput = document.querySelector('#webshop-content .webshop-global-search');
  if(e.key === 'Enter' && e.target === globalSearchInput) {
    queryBuilder(['global_search', globalSearchInput.value]);
  }
});

document.addEventListener('click',function(e){
  if(e.target.classList.contains('webshop-hamburger-menu') || e.target.closest('.webshop-hamburger-menu')) {
    e.preventDefault();
    e.stopPropagation();
    
    let mobile_menu = document.getElementsByClassName("webshop-mobile-menu")[0];
    mobile_menu.classList.toggle("hidden");
    document.body.classList.toggle("overflow-hidden");
  } else if(e.target.classList.contains('webshop-mobile-top-item-link')) {
    e.preventDefault();
    e.stopPropagation();

    let mobile_menu_header = document.getElementsByClassName("webshop-mobile-nav-header")[0];
    let current_cat_view_all_wrapper = document.querySelector(".view-current-cat");
    let current_cat_view_all = document.querySelector(".view-current-cat a");
    
    mobile_menu_header.innerText = e.target.innerText;
    current_cat_view_all_wrapper.classList.remove('hidden');
    current_cat_view_all.setAttribute('data-embed_url', e.target.getAttribute('data-embed_url'));
    current_cat_view_all.setAttribute('href', e.target.getAttribute('href'));
    
    let top_cat_container = document.getElementsByClassName("webshop-mobile-top-category-container")[0];
    
    let top_category_uid = e.target.getAttribute('data-category_uid');
    let sub_cat_containers = document.getElementsByClassName("webshop-mobile-parent-cat-"+top_category_uid);
    if(sub_cat_containers.length) {
      for(let i=0; i<sub_cat_containers.length; i++) {
        sub_cat_containers[i].classList.add("active");
      }
    }

    top_cat_container.classList.toggle('hidden')
  } else if(e.target.classList.contains('webshop-mobile-second-sub')) {
    e.preventDefault();
    e.stopPropagation();

    let mobile_menu_header = document.getElementsByClassName("webshop-mobile-nav-header")[0];
    mobile_menu_header.innerText = e.target.innerText;

    let current_cat_view_all_wrapper = document.querySelector(".view-current-cat");
    let current_cat_view_all = document.querySelector(".view-current-cat a");

    current_cat_view_all_wrapper.classList.remove('hidden');
    current_cat_view_all.setAttribute('data-embed_url', e.target.getAttribute('data-embed_url'));
    current_cat_view_all.setAttribute('href', e.target.getAttribute('href'));
    
    let parent = e.target.closest('.webshop-sub-category');

    let all_sub_cats = document.querySelectorAll('.webshop-sub-category');
    for(let i=0; i<all_sub_cats.length; i++) {
      all_sub_cats[i].classList.remove("active");
    }
    parent.classList.add("active");
    
    let children = parent.querySelector('.mobile-menu-row');
    if(children) {
      children.classList.add("active");
    }
  } else if(e.target.classList.contains('webshop-close-mobile-menu')) {
    closeMobileCategories();
  } else if(!e.target.closest('.webshop-mobile-menu') && !e.target.classList.contains('webshop-global-search') && !e.target.classList.contains('disabled')) {
    document.body.classList.remove("overflow-hidden");
  }
});

function closeMobileCategories() {
  document.body.classList.remove("overflow-hidden");
  
  let all_sub_cats = document.querySelectorAll('.webshop-sub-category');
  for(let i=0; i<all_sub_cats.length; i++) {
    all_sub_cats[i].classList.remove("active");
  }

  let top_cat_container = document.getElementsByClassName("webshop-mobile-top-category-container")[0];
  top_cat_container.classList.remove("hidden");

  let mobile_menu = document.getElementsByClassName("webshop-mobile-menu")[0];
  mobile_menu.classList.add("hidden");

  let mobile_menu_header = document.getElementsByClassName("webshop-mobile-nav-header")[0];
  mobile_menu_header.innerText = mobile_menu_header.getAttribute('data-default_title');

  let current_cat_view_all_wrapper = document.querySelector(".view-current-cat");
  current_cat_view_all_wrapper.classList.add('hidden');
}