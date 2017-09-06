    document.addEventListener("DOMContentLoaded", function() {
        var tabs = document.querySelectorAll('.tabbed li');


        for (var i = 0, len = tabs.length; i < len; i++) {
            tabs[i].addEventListener("click", function() {
                if (this.classList.contains('active'))
                    return;

                var parent = this.parentNode,
                    innerTabs = parent.querySelectorAll('li');

                for (var index = 0, iLen = innerTabs.length; index < iLen; index++) {
                    innerTabs[index].classList.remove('active');
                }

                this.classList.add('active');
            });
        }
    });

