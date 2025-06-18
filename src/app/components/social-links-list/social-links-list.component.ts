import { Component } from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {NgForOf} from '@angular/common';

@Component({
  selector: 'app-social-links-list',
  imports: [NgForOf],
  templateUrl: './social-links-list.component.html',
  styleUrl: './social-links-list.component.scss'
})
export class SocialLinksListComponent {
  socialLinks: SocialLink[] = [];
  constructor(private http: HttpClient) {}

  ngOnInit() {
    this.http.get<{socialLinks: SocialLink[]}>('assets/social-links.json')
      .subscribe(data => this.socialLinks = data.socialLinks);
  }
}
