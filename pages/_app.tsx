import type { AppProps } from 'next/app';
import '../styles/shop.css';

function MyApp({ Component, pageProps }: AppProps) {
  return <Component {...pageProps} />;
}

export default MyApp;
