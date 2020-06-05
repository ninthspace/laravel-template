const inDevelopment = (process.env.NODE_ENV !== 'production');

class TailwindExtractor {
    static extract(content) {
        // return content.match(/[A-z0-9-:\/]+/g);
        return content.match(/[\w-/:]+(?<!:)/g) || [];
        // return content.match(/[A-z0-9-:\/]+/g) || [];
    }
}

module.exports = {
    plugins: [
        require('postcss-import'),
        require('tailwindcss')('./tailwind.config.js'),
        require('postcss-nested'),
        require('autoprefixer'),
    ].filter(Boolean),
};
