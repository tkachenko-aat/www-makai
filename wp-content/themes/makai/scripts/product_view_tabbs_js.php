<script type="text/javascript">
// Таби 
function getChildElementsByClassName(parentElement, className) {
	var childElements = parentElement.getElementsByClassName(className);
	return childElements;
}

function BuildTabs(containerId) {
	var i, tabContainer, tabContents, tabHeading, title, tabElement;
	var divElement, ulElement, liElement, tabLink, linkText;
	// assume that if document.getElementById exists, then this will work...
	if(! eval('document.getElementById') ) return;
	tabContainer = document.getElementById(containerId);
	if(tabContainer == null)
		return;
	tabContents = getChildElementsByClassName(tabContainer, 'tab-content');
	if(tabContents.length == 0)
		return;
	divElement = document.createElement("div");
  	divElement.className = 'tab-header'
  	divElement.id = containerId + '-header';
	ulElement = document.createElement("ul");
  	ulElement.className = 'tab-list'

	tabContainer.insertBefore(divElement, tabContents[0]);
	divElement.appendChild(ulElement);

	for(i = 0; i < tabContents.length; i++) {
		
		tabHeading = getChildElementsByClassName(tabContents[i], 'tab');
		title = tabHeading[0].childNodes[0].nodeValue;

		// create the tabs as an unsigned list
		liElement = document.createElement("li");
		liElement.id = containerId + '-tab-' + i;

		tabLink = document.createElement("a");
		tabLink_span = document.createElement("span");
		linkText = document.createTextNode(title);

		tabLink.className = "tab-item";

		// tabLink.setAttribute("href","javascript://");
		tabLink.setAttribute( "title", title);
		tabLink.onclick = new Function ("ActivateTab('" + containerId + "', " + i + ")");

		ulElement.appendChild(liElement);
		liElement.appendChild(tabLink);
		tabLink.appendChild(tabLink_span);		
		tabLink_span.appendChild(linkText);

		// remove the H1
	//	tabContents[i].removeChild
	}
}

function ActivateTab(containerId, activeTabIndex) {
	var i, tabContainer, tabContents;

	tabContainer = document.getElementById(containerId);
	if(tabContainer == null)
		return;

	tabContents = getChildElementsByClassName(tabContainer, 'tab-content');
	if(tabContents.length > 0)  {
		for(i = 0; i < tabContents.length; i++) {
			// tabContents[i].className = "tab-content";
			 tabContents[i].style.display = "none";
		}
		tabContents[activeTabIndex].style.display = "block";

    		tabList = document.getElementById(containerId + '-list');
		tabs = getChildElementsByClassName(tabContainer, 'tab-item');
		if(tabs.length > 0) {
			for(i = 0; i < tabs.length; i++) {
				tabs[i].className = "tab-item";
				tabs[i].parentNode.className = "tabe";
			}

			tabs[activeTabIndex].className = "tab-item tab-active";
			tabs[activeTabIndex].parentNode.className = "act";
			tabs[activeTabIndex].blur();
		}
	}
}

</script>