new Splide( '#awf-goats-carousel', {
    type   : 'loop',
    perPage: 3,
    autoWidth: true,
    autoHeight: true,
    cover  : true,
	breakpoints: {
		640: {
			perPage: 2,
		},
	}
}).mount();
