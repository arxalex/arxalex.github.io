import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SocialLinksListComponent } from './social-links-list.component';

describe('SocialLinksListComponent', () => {
  let component: SocialLinksListComponent;
  let fixture: ComponentFixture<SocialLinksListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [SocialLinksListComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(SocialLinksListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
