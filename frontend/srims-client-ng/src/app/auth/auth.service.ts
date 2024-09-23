import { Injectable, inject} from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { tap } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  httpClient = inject(HttpClient);
  baseUrl = 'http://localhost:8080/api/v1';

  constructor() { }

  login(data: any) {
    return this.httpClient.post(`${this.baseUrl}/login`, data)
      .pipe(tap((result) => {
        localStorage.setItem('authUser', JSON.stringify(result));
      }));
  }

  isLoggedIn() {
    return localStorage.getItem('authUser') !== null;
  }

  logout() {
    localStorage.removeItem('authUser');
  }
}
