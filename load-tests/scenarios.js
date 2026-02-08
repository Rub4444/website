/**
 * k6 load tests: catalog, cart add, login, checkout (view).
 * Run against STAGING/TEST environment only. Do not run against production with write scenarios.
 *
 * Required: BASE_URL (e.g. https://staging.example.com)
 * Optional: SKU_ID (for cart add), LOGIN_EMAIL, LOGIN_PASSWORD (for login scenario)
 *
 * Run: k6 run load-tests/scenarios.js -e BASE_URL=https://staging.example.com
 */
import http from 'k6/http';
import { check, sleep } from 'k6';
import { Rate } from 'k6/metrics';

const errorRate = new Rate('errors');

export const options = {
  stages: [
    { duration: '30s', target: 100 },
    { duration: '1m', target: 100 },
    { duration: '1m', target: 300 },
    { duration: '2m', target: 500 },
    { duration: '1m', target: 0 },
  ],
  thresholds: {
    http_req_duration: ['p(95)<5000', 'p(99)<10000'],
    http_req_failed: ['rate<0.1'],
    errors: ['rate<0.1'],
  },
};

const BASE_URL = __ENV.BASE_URL || 'http://localhost';
const SKU_ID = __ENV.SKU_ID || '';
const LOGIN_EMAIL = __ENV.LOGIN_EMAIL || '';
const LOGIN_PASSWORD = __ENV.LOGIN_PASSWORD || '';

function extractCsrf(html) {
  const match = html.match(/name="_token"\s+value="([^"]+)"/) || html.match(/content="([^"]+)"\s+name="csrf-token"/);
  return match ? match[1] : '';
}

export function catalogScenario() {
  const urls = [
    `${BASE_URL}/`,
    `${BASE_URL}/shop`,
    `${BASE_URL}/categories`,
  ];
  const url = urls[Math.floor(Math.random() * urls.length)];
  const res = http.get(url, { tags: { name: 'catalog' } });
  const ok = check(res, { 'catalog status 200': (r) => r.status === 200 });
  if (!ok) errorRate.add(1);
  sleep(0.5 + Math.random());
}

export function cartAddScenario() {
  if (!SKU_ID) {
    sleep(1);
    return;
  }
  const getRes = http.get(`${BASE_URL}/`, { tags: { name: 'cart_get' } });
  const cookies = { 'XSRF-TOKEN': getRes.cookies.XSRF_TOKEN && getRes.cookies.XSRF_TOKEN[0] ? decodeURIComponent(getRes.cookies.XSRF_TOKEN[0].value) : '' };
  const csrf = extractCsrf(getRes.body) || cookies['XSRF-TOKEN'];
  const payload = JSON.stringify({
    quantity: 1,
    _token: csrf,
  });
  const headers = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-XSRF-TOKEN': csrf,
    'Cookie': getRes.headers['Set-Cookie'] ? getRes.headers['Set-Cookie'].join('; ') : '',
  };
  const res = http.post(`${BASE_URL}/cart/add/${SKU_ID}`, payload, { headers, tags: { name: 'cart_add' } });
  const ok = check(res, { 'cart add status 200': (r) => r.status === 200 });
  if (!ok) errorRate.add(1);
  sleep(0.5 + Math.random());
}

export function loginScenario() {
  if (!LOGIN_EMAIL || !LOGIN_PASSWORD) {
    sleep(1);
    return;
  }
  const getRes = http.get(`${BASE_URL}/login`, { tags: { name: 'login_get' } });
  const csrf = extractCsrf(getRes.body);
  const form = {
    email: LOGIN_EMAIL,
    password: LOGIN_PASSWORD,
    _token: csrf,
  };
  const res = http.post(`${BASE_URL}/login`, form, {
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    tags: { name: 'login_post' },
  });
  const ok = check(res, { 'login status 200': (r) => r.status === 200 });
  if (!ok) errorRate.add(1);
  sleep(0.5 + Math.random());
}

export function checkoutViewScenario() {
  const getRes = http.get(`${BASE_URL}/`, { tags: { name: 'checkout_get' } });
  const res = http.get(`${BASE_URL}/basket/place`, {
    headers: { Cookie: getRes.headers['Set-Cookie'] ? getRes.headers['Set-Cookie'].join('; ') : '' },
    tags: { name: 'checkout_view' },
  });
  // 200 or 302 to basket (empty) is ok
  const ok = check(res, { 'checkout view ok': (r) => r.status === 200 || r.status === 302 });
  if (!ok) errorRate.add(1);
  sleep(0.5 + Math.random());
}

export default function () {
  const scenario = Math.floor(Math.random() * 4);
  if (scenario === 0) catalogScenario();
  else if (scenario === 1) cartAddScenario();
  else if (scenario === 2) loginScenario();
  else checkoutViewScenario();
}
