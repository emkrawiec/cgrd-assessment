// vite.config.ts
import {defineConfig} from 'vite';

export default defineConfig({
    server: {
        origin: 'http://localhost:5173',
    },
    build: {
        manifest: true,
        outDir: './public',
        emptyOutDir: false,
        rollupOptions: {
            input: './src/frontend/ts/script.ts',
            output: {
                assetFileNames: '[name].[ext]',
                entryFileNames: '[name].js',
            }
        },
    },
})