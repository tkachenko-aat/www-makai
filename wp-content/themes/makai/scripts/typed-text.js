$(function () {
    var typed = new Typed('.home_text .page_title h1 span', {
        strings: [' are <i>powerful</i> and inspiring.', ' are unique and <i>beautiful</i>.', ' <i>captivate</i> your audience.', ' are <i>SEO\xA0friendly</i>.', ' are\xA0<i>sustainable</i>.', ' are\xA0<i>cutting edge</i>.', ' <i>boost</i> digital presence.', ' make a\xA0<i>lasting</i> impression.'],
        typeSpeed: 45,
        backSpeed: 40,
        backDelay: 1700,
        // shuffle: true,
        loop: true,
        loopCount: Infinity,
        contentType: 'html'
    });
});