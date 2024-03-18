// document.addEventListener("DOMContentLoaded", function () {
//     // Set the default color based on the active tab at page load
//     var activeTab = document.querySelector(".tab-button.active");
//     if (activeTab) {
//         var defaultColor = activeTab.getAttribute("data-color");
//         document.documentElement.style.setProperty(
//             "--active-tab-color",
//             defaultColor
//         );
//     }

//     window.openTab = function (event, tabName) {
//         // Get all elements with class="tab-content" and hide them
//         var tabcontent = document.getElementsByClassName("tab-content");
//         for (var i = 0; i < tabcontent.length; i++) {
//             tabcontent[i].style.display = "none";
//         }

//         // Get all elements with class="tab-button" and remove the class "active"
//         var tablinks = document.getElementsByClassName("tab-button");
//         for (var i = 0; i < tablinks.length; i++) {
//             tablinks[i].classList.remove("active");
//         }

//         // Show the current tab, and add an "active" class to the button that opened the tab
//         document.getElementById(tabName).style.display = "block";
//         event.currentTarget.classList.add("active");

//         // Read the data-color attribute of the clicked tab
//         var color = event.currentTarget.getAttribute("data-color");

//         // Update the CSS variable for the active tab color
//         document.documentElement.style.setProperty("--active-tab-color", color);
//     };
// });

// // function openTab(evt, tabName) {
// //     var i, tabcontent, tabbuttons;
// //     tabcontent = document.getElementsByClassName("tab-content");
// //     for (i = 0; i < tabcontent.length; i++) {
// //         tabcontent[i].style.display = "none";
// //     }
// //     tabbuttons = document.getElementsByClassName("tab-button");
// //     for (i = 0; i < tabbuttons.length; i++) {
// //         tabbuttons[i].className = tabbuttons[i].className.replace(
// //             " active",
// //             ""
// //         );
// //     }
// //     document.getElementById(tabName).style.display = "block";
// //     evt.currentTarget.className += " active";
// // }
