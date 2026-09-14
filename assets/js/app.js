document.querySelectorAll('.confirm').forEach(x=>x.addEventListener('click',e=>{if(!confirm('Are you sure?'))e.preventDefault();}));
