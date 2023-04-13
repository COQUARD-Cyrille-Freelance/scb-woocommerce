/**
 * @typedef {import('@roots/bud').Bud} Bud
 *
 * @param {Bud} bud
 */
module.exports = async bud => {
    bud.externals({
        jQuery: 'window.jquery',
        wp: 'window.wp',
    })
    await bud
        .setPath('@dist', '../assets')
        .entry({
            app: 'app.js',
        })
}
